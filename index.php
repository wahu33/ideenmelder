<?php

/** *****************************
 * Ideenmelder
 * Autor: Walter Hupfeld, Hamm
 * E-Mail: info@hupfeld-software.de
 * Version: 1.0
 * Datum: 18.05.2021
 ******************************** */


    if (!file_exists("db/locations.db")) {
        header("Location: setup.php");
    }
    require("config.php");    
    require_once("lib/functions.php");

    $ref=(isset($_GET['ref']) && ($_GET['ref']==1));

    $strIntro ="<h4>".$strTitle."</h4>";
    $strIntro .= nl2br2($strIntroText);


    $strSQL="SELECT loc.*,f.filename FROM location loc LEFT JOIN  files f ON loc.id=f.loc_id"; 

    /* Für die Auswertung nur bestimmte Kategorien anzeigen
    $numDefect="(6,8,21)";
    $strSQL="SELECT loc.*,f.filename FROM location loc LEFT JOIN  files f ON loc.id=f.loc_id WHERE defect  in ".$numDefect;
    */
        
    $result = $db->query($strSQL);
    $arrMarker = array();
    $arrDescription = array();
    
    while ($row = $result->fetchArray()) {
        $id=$row['id'];
        $topic = $row['topic'];
        $numLng = $row['lng'];
        $numLat = $row['lat'];
        $strDescription=generate_tooltip_description($row);
        if ($boolActive) {
            $arrDescription[$id] = $strDescription;
        }
        $arrMarker[]="marker[".$id."] = [L.marker([".$numLat."," .$numLng."],"
                     ." { icon: ".$arrMarkerType[$topic]." }),'check_".$topic."'];\n"
                     ."marker[".$id."][0].addTo(mymap);\n "
                     ."marker[".$id."][0].bindPopup('".$strDescription."');";
    }
    $markerid=$id+1;
/*
 marker[val.id] = [L.marker([val.lat, val.lng], { "icon": L.MakiMarkers.icon({ "color": color, "size": "m", "icon": "circle" }) }).bindPopup(html), val.membertype];
            marker[val.id][0].addTo(map);
*/
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Walter Hupfeld, info@hupfeld-software.de">
    <meta name="description" content="Georeferenzieter Ideenmelder">



    <script src="js/jquery.min.js"></script>
    <script src="js/leaflet.js"></script>
    <script src="js/leaflet.ajax.js"></script>
    <script src="js/leaflet.awesome-markers.js"></script>
    <script src="js/leaflet.snogylop.js"></script>
    <script src="js/lightbox.min.js"></script>

    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/leaflet.css" />
    <link rel="stylesheet" href="css/leaflet.awesome-markers.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/lightbox.css" />

    <title>Ideenmelder</title>
</head>

<body>

  <!--  Navbar -->
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#"><?= $strTitle ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
        <div class="collapse navbar-collapse" id="navbars">
       
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php?ref=1">Karte <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="liste.php">Liste</a>
                </li>
            </ul>
            
           <div>
            <ul class="navbar-nav mr-auto right">
                <li class="nav-item">
                    <a class="nav-link" href="impressum.php">Impressum</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="datenschutz.php">Datenschutzerklärung</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin/login.php">Login</a>
                </li>
            </ul>
        </div>
</div>
    </nav>
    <!-- Ende Navbar -->

    <div class="container-fluid" style="margin-top: 4em;">
        <div class="row">

            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                <img class="logo" src="<?=$strLogo?>" alt="Logo" >
                    <ul class="nav flex-column">
<?php
                    foreach ($arrTopic as $key=>$topic) {
                        echo "<li class='nav-item'>";
                        echo "<label>";
                        echo "<a class='nav-link'>".$arrIcon[$key]." ";
                        echo "<input type='checkbox' name='check_".$key."' id='check_".$key."' class='check' checked='checked' > ";
                        echo $topic."</a></label>";
                        echo "</li>";
                    }
?>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-1">
                <div id="mapid"></div>
            </main>

        </div>
        <!-- row -->
    </div>
    <!-- container-fluid -->
   <?php
        if ($boolActive) {
            require("lib/dialog_karte.php");
            require("lib/dialog_comment.php");
        }
   ?>
   <div id="loader"><img src="css/images/ajax-loader.gif"></div>
    <script>


    // Map ---------------------------------------------------------------------- 

    var mymap = L.map('mapid').setView([<?=$numInfoLat ?>, <?=$numInfoLng ?>], <?=$numZoom ?>);

    var mapLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';
   // ocmlink = '<a href="http://thunderforest.com/">Thunderforest</a>'; 
    var ocmLink = '<a href="https://www.mapbox.com/">Mapbox</a>';
        
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        minZoom:<?=$numZoom ?>,
        attribution: 'Map data &copy; '+ mapLink +' contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © ' + ocmLink,
        //id: 'mapbox/streets-v11',
        id: 'mapbox/outdoors-v11',
        tileSize: 512,
        zoomOffset: -1
        }).addTo(mymap);

    //  markerIcons  ------------------------------------------------------------------------------

    var infoMarker    = L.AwesomeMarkers.icon({icon: 'info', prefix: 'fa', markerColor: 'orange'});
    var bicycleMarker = L.AwesomeMarkers.icon({icon: 'bicycle', prefix: 'fa', markerColor: 'green'});
    var carMarker     = L.AwesomeMarkers.icon({icon: 'car', prefix: 'fa', markerColor: 'red'});
    var truckMarker   = L.AwesomeMarkers.icon({icon: 'truck', prefix: 'fa', markerColor: 'beige'});
    var trainMarker   = L.AwesomeMarkers.icon({icon: 'bus', prefix: 'fa', markerColor: 'blue'});
    var pedestrianMarker = L.AwesomeMarkers.icon({icon: 'male', prefix: 'fa', markerColor: 'darkblue'});

    function getMarker(topic) {
        var arrMarker = [];
        <?php
          foreach ($arrMarkerType as $key => $value) {
            echo "arrMarker[".$key."]=".$value.";\n";
          } 
        ?>
        return arrMarker[topic];
    }

    //Hamm-Layer - todo invers area -----------------------------

    var myStyle = {
        "color": "grey",
        "fillColor": "lightblue",
        "weight": 4,
        "opacity": 0.6
    };

    
    // Sonderfall Unna mit Gemeindegrenzen
    var gemeindeStyle = {
        "color": "grey",
        "fillColor": "white",
        "weight": 2,
        "opacity": 0.6
    };

    var unnaLayer = new L.GeoJSON.AJAX(["geojson/KommunenKU.geojson"],{style:gemeindeStyle}); 
    unnaLayer.addTo(mymap); 
    

    var hammLayer = new L.GeoJSON.AJAX(["<?= $fileGeojson ?>"], {  
        style: myStyle,
        invert: true
    });
    hammLayer.addTo(mymap);
    
    // Marker from database  -------------------------------------------------------

    var up="up";
    var down="down";
    var marker = [];
    var arrDescription = [];
    
    <?php
        foreach ($arrMarker as $idx=>$strMarker) {
            echo $strMarker."\n";
        }

        if ($boolComment) {
            foreach ($arrDescription as $index => $value) {
                echo "arrDescription[".$index."]=\"$value\"\n";
            }
        }
        echo "var marker_max=".$markerid."\n";
    ?>

    // Info-Marker für Start --------------------------------------------------------

    var marker2 = L.marker([<?=$numInfoLat?>, <?=$numInfoLng?>], { icon: infoMarker }).addTo(mymap);
    <?php
        if ($ref) {
            echo "marker2.bindPopup('".$strIntro."');";
        } else {
            echo "marker2.bindPopup('".$strIntro."').openPopup();";
        }
    ?>

    //  Editor ----------------------------------------------------------------------

    var edit = <?= ($boolActive) ? "true" : "false" ?>;
    var myMarker;
    var lat;
    var lng;

    function onMapClick(e) {
        if (!edit) return false;
        edit = false;
        lat=e.latlng.lat;
        lng=e.latlng.lng;
        myMarker = L.marker([e.latlng.lat, e.latlng.lng], {
                title: "Mein Punkt",
                alt: "Informationspunkt",
                draggable: true,
            })
            .addTo(mymap)
            .on('dragend', function() {
                var coord = String(myMarker.getLatLng()).split(',');
                var lata = coord[0].split('(');
                var lnga = coord[1].split(')');
                lat = lata[1]; lng = lnga[0];
                myMarker.bindPopup("Bewegt zu: " + lata[1] + ", " + lnga[0] + ".");
                //console.log(lat+" "+lng);
            });
        $("#dialog").show();
    };
     
    hammLayer.on('click',function(e){ e.preventDefault(); })
    mymap.on('click', onMapClick);

    // Close Dialog
    $("#close").click(function(e) {
        $("#dialog").hide();
        $("#description").val("").empty();
        $("#defect").val(0);
        $("#topic").val(2);
        $("#photo").val("");
        edit = true;
        mymap.removeLayer(myMarker);
    })


    // Ajax-Based submit
    $("#newobjectform").submit(function(event){
        $("#lat").val(lat);
        $("#lng").val(lng);
        topic=$('input[name=topic]:checked').val();
        newMarker = getMarker(topic);
        event.preventDefault();
 
        //grab all form data  
        var formData = new FormData($(this)[0]);
        console.log(formData);
        $.ajax({
            type: "POST",
            url: "ajax/ajax_location_push.php",
            enctype: 'multipart/form-data',
            data: formData,     //$("#newobjectform").serialize(), // serializes the form's elements.
            processData: false, 
            contentType: false,
            cache: false,
            beforeSend : function () {
                $("#loader").show();
            },
            success: function(data)
            {
                $("#dialog").hide();
                $("#description").val("").empty();
                $("#defect").val(0);
                $("#topic").val(2);
                $("#photo").val("");
                popuptext=data;
                console.log(data);
                marker[marker_max] = L.marker([lat,lng], { icon: newMarker }).addTo(mymap);
                marker[marker_max].bindPopup(popuptext);
                marker_max++;
                edit=true;
                mymap.removeLayer(myMarker);
                $("#btnSubmit").prop("disabled", false);

                event.preventDefault();
               
                // description = arrDescription[id]+"<div class='comment'><em>"+username+" schrieb am "+today+"</em><br>"+comment+"</div>";
                // marker[id][0].bindPopup(description);
            },
            complete: function() {
                $("#loader").hide();
            },
            error: function(data)
            {
                alert('Fehler: Konnte keine Daten senden!'); // show response from post.
            }
        });
        edit=true;
        mymap.removeLayer(myMarker);
        return false;
    });

    // Hide and show marker form checkbox  ----------------------------------------

    $('.check').click(function() {
        bereich = this.name;
        if (jQuery(this).prop("checked")) {
            jQuery.each(marker, function(key, value) {
                if (value) {
                    if (value[1] == bereich) { value[0].addTo(mymap); }
                }
            });
        } else {
            jQuery.each(marker, function(key, value) {
                if (value) {
                    if (value[1] == bereich) { value[0].remove(); }
                }
            });
        }
    })

    // Rating  ----------------------------------------------------------------
    
    function thumb_up_down(id,num,mode) {
        $.ajax({
            type: 'POST',
            url: 'ajax/ajax_rating.php',
            data: 'mode='+mode+'&id='+id+'&value='+num+'',
            success: function(data){
                if (data=="error") alert("Fehler");
            },
            dataType: 'html'
        });
        alert("Vielen Dank für Ihre Bewertung!");
        id="#"+mode+"s_"+id;
        num=num+1;
        $(id).html(num);
    }

    // Comment -------------------------------------------------------------------

    function open_comment(id) {
        $("#dialog_comment").show();
        $("#loc_id").val(id);
        $('#comment').val("");
    }

    $("#close_comment").click(function(e) {
        $("#dialog_comment").hide();
    })

    $("#commentform").submit(function(event){
        //$(".ajax-wait-panel").addClass("ajax-waiting");

        //console.log($("#commentform").serialize());
        $.ajax({
            type: "POST",
            url: "ajax/ajax_comment_push.php",
            data: $("#commentform").serialize(), // serializes the form's elements.
            success: function(data)
            {
                $("#dialog_comment").hide();
                var id  =  $("#loc_id").val();
                var username = $("#comment_username").val();
                var comment  = $("#comment").val();
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                today = dd + '.' + mm + '.' + yyyy;
                description = arrDescription[id]+"<div class='comment'><em>"+username+" schrieb am "+today+"</em><br>"+comment+"</div>";
                marker[id][0].bindPopup(description);
            },
            error: function(data)
            {
                alert('Fehler: Konnte keine Daten senden!'); // show response from post.
            }
            //$(".ajax-wait-panel").removeClass("ajax-waiting"); 
            });
        return false; // avoid to execute the actual submit of the form.
        event.preventDefault();
    });

    // Check image upload  ------------------------------------------------------

    $("#photo").on('change', function (e) {
        var image_ok = true;
        var file, img;
        var _URL = window.URL || window.webkitURL;
        if ((file = this.files[0])) {
            var file = this.files[0];
            var fileType = file["type"];
            var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
            if ($.inArray(fileType, validImageTypes) < 0) {
                image_ok=false;
                alert("Keine Bilddatei, nur gif,jpeg,png erlaubt.")
                $("#photo").val("");
            }
            if (image_ok) {
                img = new Image();
                var objectUrl = _URL.createObjectURL(file);
                img.onload = function () {
                    image_ok = (this.width<2000 && this.height<2000 && this.width>100 && this.height>100);
                    console.log("Breite:" + this.width + "   Höhe: " + this.height);
                    if (!image_ok) {
                        alert("Bilder dürfen maximal 2000 x 2000 Pixel groß sein.\n"
                                +"Breite:" + this.width + "   Höhe: " + this.height)
                    }
                };
                img.src = objectUrl;
            }
        }
    });

    </script>
</body>
</html>
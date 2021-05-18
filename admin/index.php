<?php

/** *****************************
 * Ideenmelder
 * Autor: Walter Hupfeld, Hamm
 * E-Mail: info@hupfeld-software.de
 * Version: 1.0
 * Datum: 18.05.2021
 ******************************** */


    session_start();
    $strLoginName=(isset($_SESSION['user'])) ? $_SESSION['user'] : "" ;
    $boolLogin = (!empty($strLoginName));
    if (!$boolLogin) {
        header("Location: login.php");
    }

    $dbFilename = "../db/locations.db";
    include("../config.php");
    $boolShowmap=false;

    if (isset($_GET['delid'])) {
        if($_GET['csrf'] !== $_SESSION['csrf_token']) {
            die("Ungültiger Token");
        }
        $numDelete = (int)$_GET['delid'];

        $stmt = $db->prepare("DELETE FROM location WHERE id = :id");
        $stmt->bindValue(":id",$numDelete);
        $stmt->execute();

        $stmt = $db->prepare("DELETE FROM comment WHERE loc_id= :loc_id");
        $stmt->bindValue(":loc_id",$numDelete);
        $stmt->execute();

        $stmt = $db->prepare("SELECT * FROM files where loc_id = :loc_id");
        $stmt->bindValue(":loc_id", $numDelete, SQLITE3_TEXT);
        $result = $stmt->execute();
        if ($row = $result->fetchArray()) {
            $strFilename = $row['filename'];
            $strFilename = $uploaddir . $strFilename;
            unset($strFilename);
        }

        $stmt = $db->prepare("DELETE FROM files WHERE loc_id= :loc_id");
        $stmt->bindValue(":loc_id",$numDelete);
        $stmt->execute();
    }

    if (isset($_GET['delcid'])) {
        if($_GET['csrf'] !== $_SESSION['csrf_token']) {
            die("Ungültiger Token");
        }
        $numDelete=(int)$_GET['delcid'];
        $stmt = $db->prepare("DELETE FROM comment WHERE id= :id");
        $stmt->bindValue(":id",$numDelete);
        $stmt->execute();
    }


    if (isset($_GET['delfid'])) {
        if($_GET['csrf'] !== $_SESSION['csrf_token']) {
            die("Ungültiger Token");
        }
        $numDelete=(int)$_GET['delfid'];
        $stmt = $db->prepare("SELECT * FROM files where id = :id");
        $stmt->bindValue(":id", $numDelete, SQLITE3_TEXT);
        $result = $stmt->execute();
        if ($row=$result->fetchArray()) {
            $strFilename = $row['filename'];
            $strFilename = $uploaddir . $strFilename;
            unset($strFilename);            
        }
        $stmt = $db->prepare("DELETE FROM files WHERE id= :id");
        $stmt->bindValue(":id",$numDelete);
        $stmt->execute();
    }

    if (isset($_GET['showmap'])) {
        $numShowmap=(int)$_GET['showmap'];
        $boolShowmap=$numShowmap==1;
    }

    $arrTopic = array (
        1 => "Fußverkehr",
        2 => "Radverkehr",
        3 => "Bus und Bahn",
        4 => "Pkw-Verkehr",
        5 => "Lkw-Verkehr"
    );
    
    $arrIcon = array (
        1 => "<i class='fa fa-male'></i>",
        2 => "<i class='fa fa-bicycle'></i>",
        3 => "<i class='fa fa-train'></i>",
        4 => "<i class='fa fa-car'></i>",
        5 => "<i class='fa fa-truck'></i>"
    );
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/lightbox.css" />
    <link rel="stylesheet" href="../css/leaflet.css" />
    <link rel="stylesheet" href="../css/leaflet.awesome-markers.css" />


    <script src="../js/jquery.min.js"></script>     
    <script src="../js/leaflet.js"></script>
    <script src="../js/leaflet.awesome-markers.js"></script>
    <script src="../js/lightbox.min.js"></script>
    <title>Eintragsliste</title>

    <style>
      .tdmap { height:350px; width:300px;}
    </style>
</head>
<body>

<!--  Navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">Administration <?= $strTitle ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
        <div class="collapse navbar-collapse" id="navbars">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Liste <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="configuration.php">Konfiguration </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="geocoding.php">Addressen ermitteln </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="export.php">Export </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="password.php">Passwort ändern </a>
                </li>
                
                </ul>
            
            <div>
             <ul class="navbar-nav mr-auto right">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout  (<?=$strLoginName?>)</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Ende Navbar -->

<div class="container-fluid" style="margin-top:5em;">
<table class="table table-bordered table-striped">
    <thead>
        <tr><th>id</th><th>Username</th>
        <?= ($boolUserinfo) ? "<th>Alter</th><th>Transport</th>" : "" ?>
        <th>Topic</th>
        <th>Beschreibung</th>
        <th><i class="fa fa-thumbs-up"></i></th><th><i class="fa fa-thumbs-down"></i></th>
        <th>Kommentare</th>
        <th>Adresse</th>
        <th>Mangel</th>
        <th>Bild</th>
        <th>lat/lng</th>
        <th>Datum</th>
        <th>Aktion</th></tr>
    </thead>
    <tbody>

    <?php
    $strScript="";
    //$strSQL="SELECT * FROM location ORDER BY created_at DESC";
    $strSQL="SELECT l.id as lid,l.*,adr.* FROM location l LEFT JOIN address adr ON l.id=adr.loc_id ORDER BY created_at ASC";
    $result = $db->query($strSQL);
    while ($row = $result->fetchArray()) {
        $id = $row['lid'];
        echo "<tr>";
        echo "<td>".$id."</td>";
        echo "<td>". stripslashes($row['username']) ."</td>";
        echo ($boolUserinfo) ? "<td>".$row['age']."</td><td>".$row['transport']."</td>" : "";
        echo "<td>".$arrIcon[$row['topic']]." ".$arrTopic[$row['topic']]."</td>";
        echo "<td id='desc_".$id."'>" . nl2br(stripslashes($row['description'])) . "</td>";
        echo "<td>".$row['thumb_ups']."</td>";
        echo "<td>".$row['thumb_downs']."</td>";
        echo "<td>";
        $strSQL = "SELECT id,username,comment,created_at FROM comment WHERE loc_id=".$id;
        $comments = $db->query($strSQL);
        while ($comment = $comments->fetchArray()) {
            echo "<div class='comment'>";
            echo "<em>".$comment['username']." schrieb am ";
            $numDatum =  strtotime($comment['created_at']);
            $strDatum = date("d.m.Y",$numDatum);
            echo $strDatum."</em><br>";
            echo nl2br(stripslashes($comment['comment']));
            echo "<a class='left' href='".$_SERVER['PHP_SELF']."?delcid=".$comment['id']."&csrf=".$_SESSION['csrf_token']."'><i class='fa fa-trash'></i></a>";
            echo "</div>";
        }
        echo "</td>";
        echo "<td>".$row['road']." ".$row['house_number']."<br>"
                .$row['neighbourhood']." " 
                .$row['hamlet']." "
                .$row['suburb']."</td>";
        $strDefect = (isset($row['defect']) && $row['defect']>0) ? $arrDefect[$row['defect']] : "";
        echo "<td id='defect_".$id."' value='".$row['defect']."'>".$strDefect."</td>\n";
        
        echo "<td id='img_".$id."'>";
        $strSQL = "SELECT id,filename FROM files WHERE loc_id=".$id;
        $files=$db->query($strSQL);
        if ($file=$files->fetchArray()) {
            echo "<a href='../images/".$file['filename']."' data-lightbox='radweg".$id."'>";
            echo "<img src='../images/".$file['filename']."' style='width:150px'></a>";
            echo "<a href='".$_SERVER['PHP_SELF']."?delfid=".$file['id']."&csrf=".$_SESSION['csrf_token']."'><i class='fa fa-trash'></i></a>";
        }
        echo "</td>\n";
        // Karte einblenden
        if ($boolShowmap) {
            echo "<td><div class='tdmap' id='map_".$id."'></div></td>\n";
        }
        else {
            echo "<td>".round($row['lat'],5)." ".round($row['lng'],5)."</td>";
        }
        echo "<td>".$row['created_at']."</td>";
        echo "<td><a class='del' href='".$_SERVER['PHP_SELF']."?delid=".$id."&csrf=".$_SESSION['csrf_token']."'><i class='fa fa-trash'></i></a>&nbsp;";
        echo "<a class='edit_defect' href='#' id='edit_".$id."' value='".$id."'><i class='fa fa-pencil'></i></a>";
        echo "</td>";
        echo "</tr>\n";
        if ($boolShowmap) {
            $strScript.="var mymap_".$id." = L.map(map_".$id.").setView([".$row['lat'].", ".$row['lng']."], 16);\n";
            $strScript.="L.tileLayer(url, {maxZoom: 18,minZoom:12,attribution: attribution,id: 'mapbox/streets-v11',tileSize: 512,zoomOffset: -1}).addTo(mymap_".$id.")\n";
            $strScript.="L.marker([".$row['lat'].", ".$row['lng']."], { icon: infoMarker } ).addTo(mymap_".$id.")\n\n";
        }
    }
    ?>
</tbody>
</table> 
<a class="btn btn-primary" href="../index.php?ref=1">zurück</a>   
</div>

<?php include("../lib/dialog_edit_location.php"); ?>

<script>
$( document ).ready(function() {


    var url = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';
    var attribution = 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                    '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>';
    var infoMarker    = L.AwesomeMarkers.icon({icon: 'info', prefix: 'fa', markerColor: 'orange'});


    $(".edit_defect").on("click", function(e){
        e.preventDefault();
        $('#dialog_defect').hide();
        $('#dialog_defect').css({'top':e.pageY-90,'left':e.pageX-520});
       
        id = $(this).attr("value");
        descr = $("#desc_"+id).html();
        descr =  descr.replace(/(<|&lt;)br\s*\/*(>|&gt;)/g,' ');
        $("#description").html(descr);

        defect_id=$("#defect_"+id).attr("value");
        $("#defect select").val(defect_id);
        $("#loc_id").val(id);
        $('#dialog_defect').show();
        return false;
    })

    $(".del").click(function () {
        result=confirm("Wirklich löschen?");
        return result===true;
    })

    $("#editobjectform").submit(function(event){
        event.preventDefault();
       
        //grab all form data  
        var formData = new FormData($(this)[0]);
        $.ajax({
            type: "POST",
            url: "../ajax/ajax_update.php",
            enctype: 'multipart/form-data',
            data: formData,     //$("#newobjectform").serialize(), // serializes the form's elements.
            processData: false, 
            contentType: false,
            cache: false,
            
            success: function(data)
            {
                $("#dialog_defect").hide();
                console.log(data);
                newdata=JSON.parse(data);

                console.log(newdata);
                id=newdata.id;
                $("#desc_"+id).html(newdata.description);
                $("#defect_"+id).html(newdata.defect);
                if (newdata.filename>"") {
                    img="<img src='../images/"+newdata.filename+"' style='width:150px;'>";
                    $("#img_"+id).html(img);
                }
                //$("#btnSubmit").prop("disabled", false);
                event.preventDefault();
            },
            error: function(data)
            {
                alert('Fehler: Konnte keine Daten senden!');
            }
        });
        return false;
    });

    $('#close').click(function(e){
        $('#dialog_defect').hide();
    });

    <?= $strScript ?>


});
</script>
</body>
</html>
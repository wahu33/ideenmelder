<?php

/** *****************************
 * Ideenmelder
 * Autor: Walter Hupfeld, Hamm
 * E-Mail: info@hupfeld-software.de
 * Version: 1.0
 * Datum: 18.05.2021
 ******************************** */


    if (!file_exists("config.php")) {
        header("Location: setup.php");
    }
    require("config.php");
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Walter Hupfeld, info@hupfeld-software.de">
    <meta name="description" content="Georeferenzieter Ideenmelder">

    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/leaflet.css" />
    <link rel="stylesheet" href="css/leaflet.awesome-markers.css" />
    <link rel="stylesheet" href="css/lightbox.css" />
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/DataTables/datatable.min.css">
    <link rel="stylesheet" href="vendor/DataTables/DataTables-1.10.21/css/dataTables.bootstrap4.min.css">


    <title>Eintragsliste</title>
    <script src="js/jquery.min.js"></script>
    <script src="js/leaflet.js"></script>
    <script src="js/leaflet.ajax.js"></script>
    <script src="js/leaflet.awesome-markers.js"></script>
    <script src="js/lightbox.min.js"></script>
    <script src="vendor/DataTables/datatables.js"></script>
    <script src="vendor/DataTables/DataTables-1.10.21/js/dataTables.bootstrap4.min.js"></script>

    

    <style>
      #whmap { display:none; position:absolute; top:150px; left:400px; }
      #detailmap { height:450px; width:400px;}
      .comment {  border-top: 1px solid darkgrey; padding: 3px 0 3px 0; }
    </style>
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
                <li class="nav-item">
                    <a class="nav-link" href="index.php?ref=1">Karte</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="liste.php">Liste <span class="sr-only">(current)</span></a>
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
    </nav>
    <!-- Ende Navbar -->

<div class="container-fluid main" style="max-width:1360px;">
    <p>&nbsp;</p>
<h2>Liste der Einträge</h2>

<table id="list" class="table table-bordered table-striped">
    <thead>
        <tr><th>Nr.</th><th>Name</th>
           <?= ($boolUserinfo) ? "<th>Alter</th><th>Verkehrsmittel</th>" : ""; ?>
            <th>Topic</th>
            <th>Beschreibung</th>
           <?= ($boolComment) ? "<th>Kommentare</th>" : "" ?> 
           <?= ($boolDefect) ? "<th>Mängelkategorie</th>" : ""; ?>
           <?= ($boolRating) ? "<th><i class=\"fa fa-thumbs-up\"></i></th><th><i class=\"fa fa-thumbs-down\"></i></th>" : "" ?>
           <th>Datum</th>
           <?= ($boolUpload) ? "<th>Bild</th>": "" ?>
            <th>Ort</th></tr>
    </thead>
    <tbody>

    <?php
        require_once("config.php");

        $strSQL="SELECT loc.*,f.filename FROM location loc LEFT JOIN  files f ON loc.id=f.loc_id ORDER BY created_at DESC";
        $result = $db->query($strSQL);
        $numCounter=1;
        while ($row = $result->fetchArray()) {
            $numDatum= strtotime($row['created_at']);
            $id=$row['id'];
            $datum= date("d.m.Y",$numDatum);
            $arrPoint[]= array ('id'=>$id,'lat'=>$row['lat'],'lng'=>$row['lng'],$row['description']);
            echo "<tr>";
            //echo "<td>".$numCounter." <a href='mailto:".$contactEmail."?subject=Ideenmelder Eintrag ".$id."'><i class='fa fa-envelope-o'></i>"."</td>";
            echo "<td>".$id."</td>";
            echo "<td>". stripslashes($row['username']) ."</td>";
            if ($boolUserinfo) {
                echo "<td>".$row['age']."</td>";
                echo "<td>".$row['transport']."</td>";
            }
            echo "<td>".$arrIcon[$row['topic']]." ".$arrTopic[$row['topic']]."</td>";
            echo "<td>" . nl2br(stripslashes($row['description']))."</td>";
            if ($boolComment) {
                echo "<td>";
                $strSQL = "SELECT username,comment,created_at FROM comment WHERE loc_id=".$id;
                $comments = $db->query($strSQL);
                while ($comment = $comments->fetchArray()) {
                    echo "<div class='comment'>";
                    echo "<em>".$comment['username']." schrieb am ";
                    $numDatum =  strtotime($comment['created_at']);
                    $strDatum = date("d.m.Y",$numDatum);
                    echo $strDatum."</em><br>";
            
                    echo nl2br(stripslashes($comment['comment']));
                    echo "</div>";
                }
                echo "</td>";
            }
            if  ($boolDefect) {
                $strDefect = (isset($row['defect']) && $row['defect']>0) ? $arrDefect[$row['defect']] : "";
                echo "<td>".$strDefect."</td>";
            }
            if ($boolRating) {
                echo "<td>".$row['thumb_ups']."</td>";
                echo "<td>".$row['thumb_downs']."</td>";
            }
            echo  "<td>".$datum."</td>";
            if ($boolUpload) {
                echo "<td>";
                if (isset($row['filename'])) {
                    echo "<a href='images/".$row['filename']."'  data-lightbox='radweg".$id."'>";
                    echo "<img style='width:120px' src='images/".$row['filename']."'></a>";
                }
                echo "</td>";
            }
            echo "<td><a class='maplink' name='".$id."' href='#'>Karte</a><!--".round($row['lat'],4)." / ".round($row['lng'],4)."--></td>";
            echo "</tr>\n";
            $numCounter++;
        }
    ?>
</tbody>
</table> 
<a class="btn btn-primary" href="index.php?ref=1">zurück</a>   
</div>

    <div id="whmap">
      <div id="whmap-header"> <span id="close" type="button" class="close right text-danger">
            <i class="fa fa-window-close"></i>
          </span>
      </div>
      <div id="detailmap"></div>
    </div>

</body>
<script>
jQuery(document).ready(function(){

   
    dataTable = $('#list').DataTable( {
       language: {
              url: 'vendor/DataTables/de_DE.json'
       }
   });



<?php
   echo "   var points = [];\n";
   foreach ($arrPoint as $point) {
       echo "   points[".$point['id']."]= {lat: ".$point['lat'].",lng: ".$point['lng']." }\n";
   }
?>
   var mymap = L.map('detailmap').setView([51.66, 7.82], 17);

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        minZoom:12,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1
    }).addTo(mymap);

   $('.maplink').click(function(e){
        $('#whmap').hide();
        $('#whmap').css({'top':e.pageY-90,'left':e.pageX-420});
        $('#whmap').show();
        id = $(this)[0].name;
        console.log(points[id].lat+" "+points[id].lng)
        mymap.setView([points[id].lat,points[id].lng],16);
        L.marker([points[id].lat, points[id].lng],  ).addTo(mymap);
        mymap.invalidateSize();
        e.preventDefault();
   });
   $('#close').click(function(e){
        $('#whmap').hide();
    });


});

</script>
</html>
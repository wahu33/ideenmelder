<?php

    session_start();
    $strLoginName=(isset($_SESSION['user'])) ? $_SESSION['user'] : "" ;
    $boolLogin = (!empty($strLoginName));
    if (!$boolLogin) {
        header("Location: login.php");
    }

    $dbFilename = "../db/locations.db";
    include("../config.php");



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
    <link rel="stylesheet" href="../css/leaflet.css" />
    <link rel="stylesheet" href="../css/leaflet.awesome-markers.css" />

    <script src="../js/jquery.min.js"></script>
    <script src="../js/leaflet.js"></script>
    <script src="../js/leaflet.awesome-markers.js"></script>

    <title>Eintragsliste</title>
    <style>
      .tdmap { height:350px; width:300px;}
    </style>
</head>
<body>




<div class="container-fluid">
<table class="table table-bordered table-striped">
    <thead>
        <tr><th>id</th><th>Username</th>
        <?= ($boolUserinfo) ? "<th>Alter</th><th>Transport</th>" : ""; ?>
        <th>Topic</th>
        <th>Beschreibung</th>
        <?= ($boolRating) ? "<th><i class=\"fa fa-thumbs-up\"></i></th><th><i class=\"fa fa-thumbs-down\"></i></th>" : "" ?>
        <th>Kommentare</th>
        <th>Mangel</th>
        <th>Bild</th>
        <th>Adresse</th>
        <th>Karte</th>
    </thead>
    <tbody>

    <?php
        $strScript="";
        $strSQL="SELECT l.id as lid,l.*,adr.* FROM location l LEFT JOIN address adr ON l.id=adr.loc_id ORDER BY city,postcode,suburb,hamlet,road ASC";
        $result = $db->query($strSQL);
        while ($row = $result->fetchArray()) {
            $id = $row['lid'];
            $numDatum= strtotime($row['created_at']);
            $strDatum= date("d.m.Y",$numDatum);
            echo "<tr>";
            echo "<td>".$id."</td>";
            echo "<td>". stripslashes($row['username']) ."<br><br>". $strDatum . "</td>";
            echo ($boolUserinfo) ? "<td>".$row['age']."</td><td>".$row['transport']."</td>" : "";
            echo "<td>".$arrIcon[$row['topic']]." ".$arrTopic[$row['topic']]."</td>";
            echo "<td>" . stripslashes(nl2br($row['description'])) . "</td>";
            echo ($boolRating) ? "<td>".$row['thumb_ups']."</td><td>".$row['thumb_downs']."</td>" : "";
            echo "<td>";
            $strSQL = "SELECT id,username,comment,created_at FROM comment WHERE loc_id=".$id;
            $comments = $db->query($strSQL);
            while ($comment = $comments->fetchArray()) {
                echo "<div class='comment'>";
                echo "<em>".$comment['username']." schrieb am ";
                $numDatum =  strtotime($comment['created_at']);
                $strDatum = date("d.m.Y",$numDatum);
                echo $strDatum."</em><br>";
                echo nl2br($comment['comment']);
                echo "</div>";
            }
            echo "</td>";
            $strDefect = (isset($row['defect']) && $row['defect']>0) ? $arrDefect[$row['defect']] : "";
            echo "<td>".$strDefect."</td>\n";
    
            echo "<td>";
            $strSQL = "SELECT id,filename FROM files WHERE loc_id=".$id;
            $files=$db->query($strSQL);
            if ($file=$files->fetchArray()) {
                echo "<img src='../images/".$file['filename']."' style='width:200px'>";
            }
            echo "</td>";
            echo "<td>".$row['road']." ".$row['house_number']."<br>".$row['neighbourhood']."<br>"
                   .$row['hamlet']."<br>".$row['suburb']."<br>".$row['postcode']." ".$row['city']."</td>";
            //echo "<td>".round($row['lat'],5)." ".round($row['lng'],5)."</td>";
            echo "<td><div class='tdmap' id='map_".$id."'></div></td>\n";
            echo "</tr>\n";
            $strScript.="var mymap_".$id." = L.map(map_".$id.").setView([".$row['lat'].", ".$row['lng']."], 16);\n";
            $strScript.="L.tileLayer(url, {maxZoom: 18,minZoom:12,attribution: attribution,id: 'mapbox/streets-v11',tileSize: 512,zoomOffset: -1}).addTo(mymap_".$id.")\n";
            $strScript.="L.marker([".$row['lat'].", ".$row['lng']."], { icon: infoMarker } ).addTo(mymap_".$id.")\n\n";

        }
    ?>
</tbody>
</table> 
<a class="btn btn-primary" href="../index.php?ref=1">zurück</a>   
</div>
</body>
<script>
$( document ).ready(function() {


    var url = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';
    var attribution = 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                    '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>';
    var infoMarker    = L.AwesomeMarkers.icon({icon: 'info', prefix: 'fa', markerColor: 'orange'});


    <?= $strScript ?>


});
</script>
</html>
<?php

/** *****************************
 * Ideenmelder
 * Autor: Walter Hupfeld, Hamm
 * E-Mail: info@hupfeld-software.de
 * Version: 1.0
 * Datum: 18.05.2021
 ******************************** */


function generate_tooltip_description($row) {
    global $boolRating;
    global $boolComment;
    global $boolUpload;
    global $boolDefect;
    global $uploaddir;
    global $arrTopic;
    global $arrDefect;
    global $db;

    $description = $row['description'];
    $numUps = $row['thumb_ups'];
    $numDowns = $row['thumb_downs'];
    $id = $row['id'];
    $topic = $row['topic'];
    $numDatum= strtotime($row['created_at']);
    $datum= date("d.m.Y",$numDatum);


    $strDescription = "<strong>Anmerkung zu ".$arrTopic[$topic]."</strong><br>";
    if ($boolUpload && isset($row['filename'])) {
        $strDescription .= "<a href=\'images/".$row['filename']."\' data-lightbox=\'radweg".$id."\'>";
        $strDescription .= "<img src=\'images/".$row['filename']."\' style=\'width:200px;\' /></a><br>";
    }
    $strDescription .= nl2br2($description);
    $strDescription .=  "<br> - ".$row['username']." (".$datum.")";
    if ($boolDefect) {
        if ($row['defect']>0) {
            $strDescription .= "<br><em>" . $arrDefect[$row['defect']] ."</em>";
        }
    }
    if ($boolRating) {
        $strDescription .=" <hr><div style=\'text-align:center\'>";
        $strDescription .= "<a href=\'#\' onclick=\'thumb_up_down(".$id.",".$numUps.",up)\'>";
        $strDescription .= "<i class=\'text-muted fa fa-thumbs-up\'></i></a> ";
        $strDescription .= "&nbsp;<span class=\'text-muted\' id=\'ups_".$id."\'>".$numUps."</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        $strDescription .= "<a href=\'#\' onclick=\'thumb_up_down(".$id.",".$numDowns.",down)\'>";
        $strDescription .= "<i class=\'text-muted fa fa-thumbs-down\'></i></a>";
        $strDescription .= "&nbsp;<span class=\'text-muted\' id=\'downs_".$id."\'>".$numDowns."</span></div>";
    }
    if ($boolComment) {
        $strDescription .= "<hr><div style=\'text-align:center\'>";
        $strDescription .= "<a href=\'#\' onclick=\'open_comment(".$id.")\'>Kommentar hinzufügen</a>";
        $strDescription .= "</div>";

        $strSQL = "SELECT username,comment,created_at FROM comment WHERE loc_id=".$id;
        $result = $db->query($strSQL);
        while ($comment = $result->fetchArray()) {
            $strDescription .= "<div class=\'comment\'>";
            $strDescription .= "<em>".$comment['username']." schrieb am ";
            $numDatum =  strtotime($comment['created_at']);
            $strDatum = date("d.m.Y",$numDatum);
            $strDescription .= $strDatum."</em><br>";
            $strDescription .= nl2br2($comment['comment']);
            $strDescription .= "</div>";
        }
    }
    return $strDescription;
}

/**
 *  reads gps location form picture data
 */
function read_gps_location($file){
    if (is_file($file)) {
        $info = exif_read_data($file);
        if (isset($info['GPSLatitude']) && isset($info['GPSLongitude']) &&
            isset($info['GPSLatitudeRef']) && isset($info['GPSLongitudeRef']) &&
            in_array($info['GPSLatitudeRef'], array('E','W','N','S')) && in_array($info['GPSLongitudeRef'], array('E','W','N','S'))) {

            $GPSLatitudeRef  = strtolower(trim($info['GPSLatitudeRef']));
            $GPSLongitudeRef = strtolower(trim($info['GPSLongitudeRef']));

            $lat_degrees_a = explode('/',$info['GPSLatitude'][0]);
            $lat_minutes_a = explode('/',$info['GPSLatitude'][1]);
            $lat_seconds_a = explode('/',$info['GPSLatitude'][2]);
            $lng_degrees_a = explode('/',$info['GPSLongitude'][0]);
            $lng_minutes_a = explode('/',$info['GPSLongitude'][1]);
            $lng_seconds_a = explode('/',$info['GPSLongitude'][2]);

            $lat_degrees = $lat_degrees_a[0] / $lat_degrees_a[1];
            $lat_minutes = $lat_minutes_a[0] / $lat_minutes_a[1];
            $lat_seconds = $lat_seconds_a[0] / $lat_seconds_a[1];
            $lng_degrees = $lng_degrees_a[0] / $lng_degrees_a[1];
            $lng_minutes = $lng_minutes_a[0] / $lng_minutes_a[1];
            $lng_seconds = $lng_seconds_a[0] / $lng_seconds_a[1];

            $lat = (float) $lat_degrees+((($lat_minutes*60)+($lat_seconds))/3600);
            $lng = (float) $lng_degrees+((($lng_minutes*60)+($lng_seconds))/3600);

            //If the latitude is South, make it negative. 
            //If the longitude is west, make it negative
            $GPSLatitudeRef  == 's' ? $lat *= -1 : '';
            $GPSLongitudeRef == 'w' ? $lng *= -1 : '';

            return array(
                'lat' => $lat,
                'lng' => $lng
            );
        }           
    }
    return false;
}

function nl2br2($string) {
    $string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
    return $string;
 } 
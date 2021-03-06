<?php

/** *****************************
 * Ideenmelder
 * Autor: Walter Hupfeld, Hamm
 * E-Mail: info@hupfeld-software.de
 * Version: 1.0
 * Datum: 18.05.2021
 ******************************** */

 
    date_default_timezone_set('UTC');
    if (!isset($dbFilename)) {
        $dbFilename = "db/locations.db";
    }
    $db = new SQLite3($dbFilename);

    $strSQL="select * from config";
    $result = $db->query($strSQL);

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        switch ($row['key']) {
            case "uploaddir" : 
                $uploaddir=$row['value'];
            break;
            case "stadt" :
                $strStadt=$row['value'];
            break;
            case "title" :
                $strTitle=$row['value'];
            break;
            case "fileGeojson": 
                $fileGeojson=$row['value'];
            break;
            case "InfoLat":
                $numInfoLat=$row['value'];
            break;
            case "InfoLng":
                $numInfoLng=$row['value'];
            break;
            case "zoom":
                $numZoom=$row['value'];
            break;
            case "logo":
                $strLogo=$row['value'];
            break;
            case "contactEmail":
                $contactEmail =$row['value'];
            break;
            case "impressum":
                $strImpressum =$row['value'];
            break;
            case "url":
                $strUrl   =$row['value'];
            break;
            case "UrlBez":
                $strUrlBez   =$row['value'];
            break;
            case "IntroText":
                $strIntroText=$row['value'];
            break;
            case "boolActive":
                $boolActive = ($row['value']=="1");
            break;
            case "boolRating":
                $boolRating = ($row['value']=="1");
            break;            
            case "boolComment":
                $boolComment = ($row['value']=="1");
            break;            
            case "boolUpload":
                $boolUpload = ($row['value']=="1");
            break;            
            case "boolDefect":
                $boolDefect = ($row['value']=="1");
            break; 
            case "boolUserinfo":
                $boolUserinfo = ($row['value']=="1");
            break; 

            default:
            echo "Fehler bei ".$row['key'];
            // Ende Lokalisierung
            
           break;
        }
       
    }



$arrTopic = array (
    2 => "Radverkehr",
    1 => "Fu??verkehr",
    3 => "Bus und Bahn",
//    4 => "Pkw-Verkehr",
//    5 => "Lkw-Verkehr"
); 

$arrMarkerType = array (
    1=>"pedestrianMarker",
    2=>"bicycleMarker",
    3=>"trainMarker",
    4=>"carMarker",
    5=>"truckMarker",
   );

$arrAge = array (
    1 => "keine Angabe",
    2 => "bis 14 Jahre",
    3 => "15-17 Jahre",
    4 => "18-25 Jahre",
    5 => "25-39 Jahre",
    6 => "40-64 Jahre",
    7 => "65 Jahre und ??lter",
);

$arrDefect = array (
   0 => "Keine Angabe", 
   1 => "Abrupt endender Radweg",
   2 => "Buckelpiste",
   3 => "Gef??hrliche Gleise/Schienen",
   4 => "Gehweg/Fahrr??der frei",
   5 => "Falschparker",
   6 => "Fehlende Abstellm??glichkeiten",
   7 => "Fehlende Radwege",
   8 => "Fehlender Abstellbereich",
   9 => "Fehlender taktiler Sicherheitstrennstreifen",
   10 => "Fehlende Fahrbahn??berleitung",
   11 => "Hindernisse auf Radwegen",
   12 => "Mangelhafte Radwegmarkierung/kennzeichnung",
   13 => "M??gliche gr??ne Pfeile f??r Radfahrende",
   14 => "Probleme beim Abbiegen",
   15 => "Ungen??gende Ampelschaltung",
   16 => "Ungen??gende Bordsteinabsenkung",
   17 => "Ungen??gender Sicherheitsabstand",
   18 => "Ungen??gende Wegbreite/Engstellen",
   19 => "Unsichere/fehlende Querungsm??glichkeit",
   20 => "Unklare Radwegsituation",
   21 => "Station f??r Leihr??der",
   22 => "Ampelspiegel installieren"
);


$arrTransport = array (
    0 => "keine Angabe",
    1 => "kein Auto",
    2 => "Auto",
    3 => "Motorroller/Motorrad",
    4 => "Bus/Bahn",
    5 => "Fahrrad",
    6 => "Zu Fu??"
);   

$arrIcon = array (
    1 => "<i class='wa bg-info fa fa-male'></i>",
    2 => "<i class='wa bg-success fa fa-bicycle'></i>",
    3 => "<i class='wa bg-primary fa fa-bus'></i>",
    4 => "<i class='wa bg-danger fa fa-car'></i>",
    5 => "<i class='wa bg-warning fa fa-truck'></i>"
);
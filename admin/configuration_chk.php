<?php
    session_start();
    $strLoginName=(isset($_SESSION['user'])) ? $_SESSION['user'] : "" ;
    $boolLogin = (!empty($strLoginName));
    if (!$boolLogin) {
        header("Location: login.php");
    }
    $dbFilename="../db/locations.db";
    require("../config.php");

    if($_POST['csrf'] !== $_SESSION['csrf_token']) {
        die("Ungültiger Token");
      }
    $boolActive   = (isset($_POST['active'])) ? "1" : "0";
    $boolRating   = (isset($_POST['rating'])) ? "1" : "0";
    $boolComment  = (isset($_POST['comment'])) ? "1" : "0";
    $boolUpload   = (isset($_POST['fileupload'])) ? "1" : "0";
    $boolUserinfo = (isset($_POST['userinfo'])) ? "1" : "0";
    $boolDefect   = (isset($_POST['defect'])) ? "1" : "0";


    $strUploaddir = $_POST['uploaddir'];
    $strStadt   = $_POST['district'];
    $strTitle   = $_POST['title'];
    $fileGeojson =$_POST['geojson'];
    $numInfoLat  = $_POST['lat']; 
    $numInfoLng  = $_POST['lng'];
    $numZoom     = $_POST['zoom'];
    $strLogo     = $_POST['logo'];
    $contactEmail= $_POST['contactEmail'];
    $strImpressum= $_POST['impressum'];
    $strUrl      = $_POST['url'];
    $strUrlBez   = $_POST['urlBez'];
    $strIntroText= $_POST['introtext'];

    $strImpressum=addslashes($strImpressum);
    $strIntroText=addslashes($strIntroText);

    $db->query("UPDATE config SET value= '$strUploaddir' WHERE key='uploaddir'");
    $db->query("UPDATE config SET value= '$fileGeojson' WHERE key='fileGeojson'");
    $db->query("UPDATE config SET value= '$strStadt' WHERE key='stadt'");
    $db->query("UPDATE config SET value= '$strTitle' WHERE key='title'");
    $db->query("UPDATE config SET value= '$numInfoLat' WHERE key='InfoLat'");
    $db->query("UPDATE config SET value= '$numInfoLng' WHERE key='InfoLng'");
    $db->query("UPDATE config SET value= '$numZoom' WHERE key='zoom'");
    $db->query("UPDATE config SET value= '$strLogo' WHERE key='logo'");
    $db->query("UPDATE config SET value= '$contactEmail' WHERE key='contactEmail'");
    $db->query("UPDATE config SET value= '$strImpressum' WHERE key='impressum'");
    $db->query("UPDATE config SET value= '$strUrl' WHERE key='url'");
    $db->query("UPDATE config SET value= '$strUrlBez' WHERE key='UrlBez'");
    $db->query("UPDATE config SET value= '$strIntroText' WHERE key='IntroText'");

    $db->query("UPDATE config SET value= '$boolActive' WHERE key='boolActive'");
    $db->query("UPDATE config SET value= '$boolRating' WHERE key='boolRating'");
    $db->query("UPDATE config SET value= '$boolComment' WHERE key='boolComment'");
    $db->query("UPDATE config SET value= '$boolUserinfo' WHERE key='boolUserinfo'");
    $db->query("UPDATE config SET value= '$boolDefect' WHERE key='boolDefect'");
    $db->query("UPDATE config SET value= '$boolUpload' WHERE key='boolUpload'");

    header("Location: configuration.php");



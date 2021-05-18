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
    $dbFilename="../db/locations.db";
    require ("../config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../css/font-awesome.min.css" rel="stylesheet">

    <script src="../js/jquery.min.js"></script>
    <title>Konfigruation</title>
    <style>
    .leftlabel { width: 10em;}
    input[type="text"] { width: 18em;}
    input.wide {width: 24em;}

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
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Liste <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="configuration.php">Konfiguration </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="geocoding.php">Addressen ermitteln </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="export.php">Export </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="password.php">Passwort ändern </a>
                </li>
                </ul>
            
            <div>
             <ul class="navbar-nav mr-auto right">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout (<?=$strLoginName?>)</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Ende Navbar -->






<div class="container" style="margin-top:5em;">
<h1>Export</h1>

<div class="row">
<div class="col-md-7 col-lg-7">   
<br>
<div class="card">
    <div class="card-header">
    <h3>CSV Exportieren</h3>
    </div>
    <div class="card-body">
   <ul>
    <li> <a href="print_html.php">HTML-Druckansicht</a></li>
    <li> <a href="dump.php">CSV-Datei</a></li>
    <li> <a href="shapefile.php">Shape-File (ZIP)</a></li>

   </ul>
    </div>
</div>    
<br>



</div>
</div>
</div>
</body>
</html>
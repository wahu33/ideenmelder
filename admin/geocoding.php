<?php
    session_start();
    $strLoginName=(isset($_SESSION['user'])) ? $_SESSION['user'] : "" ;
    $boolLogin = (!empty($strLoginName));
    if (!$boolLogin) {
        header("Location: login.php");
    }
    $dbFilename="../db/locations.db";
    require ("../config.php");
    require ("../lib/geocoding.php");
    $boolRefresh = (isset($_GET['refresh']) & $_GET['refresh']==1);
    $strTable="";
    if ($boolRefresh) {
        cleanAddresses($db);
        $strTable=fillAddressTable($db,20);
    }
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
                <li class="nav-item active">
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
                    <a class="nav-link" href="logout.php">Logout (<?=$strLoginName?>)</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Ende Navbar -->


<div class="container" style="margin-top:5em;">
<h1>Adressen ermitteln</h1>
<p>Bei der Eingabe der Daten werden auch die zugehörigen Adressen ermittelt. Sollte das nicht funktionieren,
können mit dieser Funktion die Adressdaten nachträglich erfasst werden. Die Erfassung der Adressen erleichtert
die Auswertung der Daten, sie werden nur im Backend angezeigt.</p>
<p><strong>Achtung:</strong> Es werden aufgrund der Beschränkungen des Dienstes 
   jeweils nur 20 Datensätze ermittelt. Ggf. muss die Seite mehrmals aufgerufen werden.</p>
<p>Geduld - der Aufruf der Funktion beansprucht etwas Zeit.</p>
<div class="row">
<div class="col-5-md">
<ul class="list-group">
<li class="list-group-item">
   <a href="<?=$_SERVER['PHP_SELF']?>?refresh=1">Adressen jetzt ermitteln</a></li>
</ul>   
<br>
</div>
</div>
<?= $strTable ?>

</div>
</body>
</html>
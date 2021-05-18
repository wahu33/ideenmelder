<?php
    session_start();
    $strLoginName=(isset($_SESSION['user'])) ? $_SESSION['user'] : "" ;
    $boolLogin = (!empty($strLoginName));
    if (!$boolLogin) {
        header("Location: login.php");
    }

    $dbFilename = "../db/locations.db";
    require_once("../config.php");
    $boolError=false;
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
                    <a class="nav-link active" href="configuration.php">Konfiguration </a>
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



<div class="container" style="margin-top:5em;">
<h1>Konfiguration</h1>
<form method="post" id="myform" action="configuration_chk.php"> 
<div class="row">
<div class="col-md-7 col-lg-7">   

<div class="card">
    <div class="card-header">
    <h3>Dateneingabe aktivieren</h3>
    </div>
    <div class="card-body">
        <input type="checkbox" id="active" name="active" <?= ($boolActive) ? "checked=\"checked\"" :"" ?> > 
        <label for="active">Dateneingabe aktivieren</label><br>
    </div>
</div>
<br>



<div class="card">
    <div class="card-header">
    <h3>Konfiguration</h3>
    </div>
    <div class="card-body">
        <input type="checkbox" id="fileupload" name="fileupload" <?= ($boolUpload) ? "checked=\"checked\"" :"" ?> > 
        <label for="fileupload">Bilder hochladen erlauben</label><br>
        <input type="checkbox" id="rating" name="rating" <?= ($boolRating) ? "checked=\"checked\"" :"" ?>> 
        <label for="rating"> Bewertungungen erlauben</label><br>
        <input type="checkbox" id="comment" name="comment" <?= ($boolComment) ? "checked=\"checked\"" :"" ?> > 
        <label for="comment">Kommentare erlauben</label><br>
        <input type="checkbox" id="defect" name="defect" <?= ($boolDefect) ? "checked=\"checked\"" :"" ?> > 
        <label for="defect">Mängelkategrien einblenden</label><br>
        <input type="checkbox" id="userinfo" name="userinfo" <?= ($boolUserinfo) ? "checked=\"checked\"" :"" ?> > 
        <label for="userinfo">Nutzerinformation (Alter/Verkehrsmittel)</label>
        <br>
        <label class="leftlabel">Uplaod-Pfad:</label>
            <input type="text" class="wide" name="uploaddir" id="uploaddir" value="<?=$uploaddir?>">
    </div>
</div>
<br>


<div class="card">
    <div class="card-header">
    <h3>Karteninfo</h3>
    </div>
    <div class="card-body">
        <h4>Stadt oder Kreis</h4>
        <label class="leftlabel">Stadt/Kreis: </label>
        <input type="text" name="district" id="district" value="<?=$strStadt?>" >
        <h4>Kartenzentrum</h4>
        <div class="small">Hier liegt das Zentrum der Karte und es erscheint der Info-Marker.</div>
        <label class="leftlabel">Latitude:</label><input type="text" name="lat" id="lat" value="<?=$numInfoLat?>"><br>
        <label class="leftlabel">Longitude:</label><input type="text" name="lng" id="lng" value="<?=$numInfoLng?>"><br>
        <div class="small">Zoom-Faktor beim Start der Karte.</div>
        <label class="leftlabel">Startzoom:</label><input type="text" name="zoom" id="zoom" value="<?=$numZoom?>">
        
        
        <h4>GeoJson</h4>
        <p>Die Datei kann man von folgender Adresse laden und ins Vezeichnis /geojson kopieren:
          <a href="https://public.opendatasoft.com/explore/dataset/landkreise-in-germany/export/">public.opendatasoft.com</a>
</p>   
        <label class="leftlabel">GeoJson-Datei: </label><input type="text" name="geojson" id="geojson" value="<?=$fileGeojson?>">
    </div>
</div> 
<br>
<div class="card">
    <div class="card-header">
    <h3>Anbieterinformation</h3>
    </div>
    <div class="card-body">
    <label class="leftlabel">Titel:</label><input type="text" name="title" id="title" value="<?=$strTitle?>"><br>
    <label class="leftlabel">Kontakt-Email:</label><input type="text" name="contactEmail" id="contactEmail" value="<?=$contactEmail?>"><br>
    <label class="leftlabel">Logo:</label><input type="text" name="logo" id="logo" value="<?=$strLogo?>"><br>
    <label class="leftlabel">Url:</label><input type="text" class="wide" name="url" id="url" value="<?=$strUrl?>"><br>
    <label class="leftlabel">Url-Text:</label><input type="text" class="wide" name="urlBez" id="urlBez" value="<?=$strUrlBez?>"><br>
    <label>Impressum: (HTML erlaubt)</label>
    <textarea id="impressum" name="impressum" rows="8" style="width:35em;"><?= stripslashes($strImpressum) ?></textarea>
     
    </div>
</div>
<br>

<div class="card">
    <div class="card-header">
    <h3>Einführungstext im Tooltipp</h3>
    </div>
    <div class="card-body">
    <label>Tooltipp-Text: (HTML erlaubt)</label>
    <textarea name="introtext" id="input" class="form-control" style="width:35em;" rows="10" required="required">
       <?=stripslashes($strIntroText) ?>
    </textarea>
    </div>
</div>    
<br>
<input type="hidden" name="csrf" value="<?=$_SESSION['csrf_token']?>">
<input type="submit" class="btn btn-primary" value="Konfiguration ändern">
</form>
<br><br><br>
</div>
</div> <!-- row -->
</div>

</body>
</html>


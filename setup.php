<?php

/** *****************************
 * Ideenmelder
 * Autor: Walter Hupfeld, Hamm
 * E-Mail: info@hupfeld-software.de
 * Version: 1.0
 * Datum: 18.05.2021
 ******************************** */


    $dbFilename = "db/locations.db";
    $boolError=false;
   
    
   
    if (file_exists("db/locations.php")) {
       die ("Datenbank existiert bereits.");
    }
?>


<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <title>Setup</title>
    <style>
    .leftlabel { width: 10em;}
    input[type="text"] { width: 18em;}
    input.wide {width: 24em;}

    </style>
</head>
<body>
<div class="container">
<h1>Setup</h1>
<form method="post" id="myform" action="setup_chk.php"> 
<div class="row">
<div class="col-md-7 col-lg-7">   
<h3>Datenbank und Bildverzeichnis einrichten</h3>
<ul class="list-group">
<li class="list-group-item">
<?php
/** **************************************************
 * 
 * Image-Folder anlegen
 * 
 **************************************************  */

 echo "Bildverzeichnis ";
 if (file_exists("images/")) {
     echo "existiert bereits";
     if (is_writable("images/")) {
         echo " und ist beschreibbar. ok";
     } else {
         echo ", ist aber nicht beschreibar. Korrigieren Sie die rechte auf dem Server.";
     }
 } else {
     // Verzeichnis anlegen
     if (mkdir("images",0755)) {
         $strImagePath=getcwd();
         $strImagePath.="/images/";
         echo $strImagePath." wurde erfolgreich angelegt";
     } else {
         echo "<span class='warning'>Fehler beim Anlegen des Bildverzeichnisses.</span>";
     }
 }
?>
</li>
<li class="list-group-item">
<?php
/** **************************************************
 * 
 * Datenbank anlegen
 * 
 **************************************************  */

echo "Datenbankverzeichnis anlegen:  ";
if (file_exists("db/locations.db")) {
    echo "Datenbank existiert bereits.";
    $boolError=true;
    die ("Abbruch");
} else {
    if (file_exists("db") && is_writable("db")) {
        echo "Datenbankverzeichnis existiert bereits";
    } else {
        if (mkdir("db",0755)) {
            echo "Datenbankverzeichnis angelegt. ";
        } else {
            echo "<span class='error'>Datenbankverzeichnis konnte nicht angelegt werden.</span>";
            $boolError=true;
        }
    }
}
?>
</li>
<li class="list-group-item">
<?php
/** **************************************************
 * 
 * Datenbank anlegen
 * 
 **************************************************  */
 
 echo "Datenbank anlegen: ";
if (!$boolError && !file_exists("db/locations.db")) {
       require("admin/create_database.php");
       echo "Datenbank angelegt";
} else {
    echo "Datenbankverzeichnis nicht angelegt oder Datenbank existiert bereits.";
}
require("config.php");
?>
</li>
</ul>

<br>
<div class="card">
    <div class="card-header">
    <h3>Nutzerdaten</h3>
    </div>
    <div class="card-body">
    <label class="leftlabel">Login: </label>
        <input type="text" name="login" id="login" value="admin"  required ><br>
    <label class="leftlabel">Passwort: </label>
        <input type="password" name="password" id="password" value="" minlength="8" required><br>
    <label class="leftlabel">Passwort (Wdh.): </label>
        <input type="password" name="password2" id="password2" value="" minlength="8" required>

    </div>
</div>    
<br>

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
        <label for="defect">Mängelkategorien einblenden</label><br>
        <input type="checkbox" id="userinfo" name="userinfo" <?= ($boolUserinfo) ? "checked=\"checked\"" :"" ?> > 
        <label for="userinfo">Nutzerinformation (Alter/Verkehrsmittel)</label>
        <br>
        <label class="leftlabel">Uplaod-Pfad:</label>
            <input type="text" class="wide" name="uploaddir" id="uploaddir" value="<?=$strImagePath?>">
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
        <input type="text" name="district" id="district" value="<?=$strStadt?>"  required >
        <h4>Kartenzentrum</h4>
        <div class="small">Hier liegt das Zentrum der Karte und es erscheint der Info-Marker.</div>
        <label class="leftlabel">Latitude:</label><input type="text" name="lat" id="lat" value="<?=$numInfoLat?>" required><br>
        <label class="leftlabel">Longitude:</label><input type="text" name="lng" id="lng" value="<?=$numInfoLng?>" required><br>
        <div class="small">Zoom-Faktor beim Start der Karte.</div>
        <label class="leftlabel">Startzoom:</label><input type="text" name="zoom" id="zoom" value="<?=$numZoom?>" required>
        
        
        <h4>GeoJson</h4>
        <p>Die Datei kann man von folgender Adresse laden und ins Vezeichnis /geojson kopieren:
          <a href="https://public.opendatasoft.com/explore/dataset/landkreise-in-germany/export/">public.opendatasoft.com</a>
</p>   
        <label class="leftlabel">GeoJson-Datei: </label><input type="text" name="geojson" id="geojson" value="<?=$fileGeojson?>" required>
    </div>
</div> 
<br>




<div class="card">
    <div class="card-header">
    <h3>Anbieterinformation</h3>
    </div>
    <div class="card-body">
    <label class="leftlabel">Titel:</label><input type="text" name="title" id="title" value="<?=$strTitle?>" required><br>
    <label class="leftlabel">Kontakt-Email:</label><input type="text" name="contactEmail" id="contactEmail" value="<?=$contactEmail?>" required><br>
    <label class="leftlabel">Logo:</label><input type="text" name="logo" id="logo" value="<?=$strLogo?>"><br>
    <label class="leftlabel">Url:</label><input type="text" class="wide" name="url" id="url" value="<?=$strUrl?>"><br>
    <label class="leftlabel">Url-Text:</label><input type="text" class="wide" name="urlBez" id="urlBez" value="<?=$strUrlBez?>"><br>
    <label>Impressum: (HTML erlaubt)</label>
    <textarea id="impressum" name="impressum" rows="8" style="width:35em;" required><?= stripslashes($strImpressum) ?></textarea>
     
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
    <?= stripslashes($strIntroText) ?></textarea>
    </div>
</div>    
<br>

<input type="submit" class="btn btn-primary" value="Konfiguration erzeugen">
</form>
<br><br><br>
</div>
</div> <!-- row -->
</div>
<script>
    $('#myform').submit(function(e){
        password1 = $("#password").val();
        password2 = $("#password2").val();
        if (password1==password2) {
            if (password1.length < 8) {
                  alert("Passwort muss mindestens 8 Zeichen haben.");
                  return false;
            } else {
                return  true;
            }
        } else {
            alert("Passwörter nicht gleich");
            return false;
            e.preventDefault();
        }
    });
</script>
</body>
</html>


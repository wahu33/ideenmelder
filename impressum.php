<!DOCTYPE html>
<html lang="de">
<?php require_once("config.php") ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Walter Hupfeld, info@hupfeld-software.de">
    <meta name="description" content="Georeferenzieter Ideenmelder">

    <title>Impressum</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
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
                <li class="nav-item">
                    <a class="nav-link" href="liste.php">Liste</a>
                </li>
                </ul>
            
            <div>
             <ul class="navbar-nav mr-auto right">

                <li class="nav-item active">
                    <a class="nav-link" href="impressum.php">Impressum <span class="sr-only">(current)</span></a>
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

    <div class="container main">
    
    <p>&nbsp;</p>
    <div class="card">
        <div class="card-header"><h2>Impressum</h2></div>
        <div class="card-body">
    <p> Der Ideenmelder wird zur Verfügung gestellt vom:</p>
    <p>&nbsp;</p>
    <div><img style="width:220px" src="<?=$strLogo?>" alt="Logo"></div>
    <p>&nbsp;</p>
    <p>
      <?= stripslashes(nl2br($strImpressum)) ?>
    </p>
    <p><a href="<?=$strUrl?>"><?=$strUrlBez?></a></p>

    <p>&nbsp;</p>
    Anfragen zum Ideenmelder an <a href="mailto:<?=$contactEmail?>"><?=$contactEmail?></a>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <!-- Bitte nicht entfernen -->
    <p class="small">Entwicklung: W. Hupfeld, Hamm<br>walter@hupfeld-hamm.de</p>

    <a class="btn btn-primary text-white" href="index.php?ref=1">zurück</a>
</div>
</div>

</div>
</body>
</html>
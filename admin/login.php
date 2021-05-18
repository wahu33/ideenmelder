<?php

/** *****************************
 * Ideenmelder
 * Autor: Walter Hupfeld, Hamm
 * E-Mail: info@hupfeld-software.de
 * Version: 1.0
 * Datum: 18.05.2021
 ******************************** */



$dbFilename = "../db/locations.db";
require_once("../config.php");
$boolLogin=true; 

    
    if (isset($_POST['login']) && isset($_POST['password'])) {
        $strUser         = trim($_POST['login']);
        $strPassword     = trim($_POST['password']);
        $strSQL = "SELECT username,passwordhash FROM user WHERE username='$strUser'";
        $result = $db->query($strSQL);
        if ($row=$result->fetchArray())  {
            if (password_verify($strPassword,$row['passwordhash'])) {
                session_start();
                $_SESSION['user']=$strUser;
                $_SESSION['csrf_token'] = uniqid('', true);
                header ("Location: index.php");
            } else {
                $boolLogin=false;
            }
        }
        else {
            $boolLogin=false;
        }
    }
    
  
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/style.css" />
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
                    <a class="nav-link" href="../index.php?ref=1">Karte</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Ende Navbar -->

    <div class="container main" style="margin-top:8em;">
    <div class="row">
    <div class="col-md-5">

    <?php if (!$boolLogin): ?>
        <div class="alert alert-danger">
        <strong>Fehler!</strong> Login nicht erfolgreich!
        </div> <br> 
    <?php endif; ?>  
    

    <div class="card">
        <div class="card-header">
        <h2>Login</h2>
        </div>
        <div class="card-body">
    <form  id="login" action="<?=$_SERVER['PHP_SELF']?>" method="post">
        <div class="form-group">
            <label for="username">Login</label>
            <input type="text" name="login" class="form-control" id="username"  placeholder="Nutzername" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Passwort" required>
        </div>
        <button type="submit" class="btn btn-primary">Absenden</button>
    </form>

    </div>
    </div>
    </div>
    </div>

    <div style="margin-top:5em;">
         <a class="btn btn-primary text-white" href="../index.php?ref=1">zurück</a>
    </div>


    </div>
</body>
</html>
<?php

/** *****************************
 * Ideenmelder
 * Autor: Walter Hupfeld, Hamm
 * E-Mail: info@hupfeld-software.de
 * Version: 1.0
 * Datum: 18.05.2021
 ******************************** */

 
    session_start();
    $dbFilename="../db/locations.db";
    require ("../config.php");
    $strLoginName=(isset($_SESSION['user'])) ? $_SESSION['user'] : "" ;
    $boolLogin = (!empty($strLoginName));
    if (!$boolLogin) {
        header("Location: login.php");
    }

    $boolError=false;
    if (isset($_POST['password1']) && isset($_POST['password2']) && isset($_POST['username'])   ) {
        if($_POST['csrf'] !== $_SESSION['csrf_token']) {
            die("Ungültiger Token");
        }
        $strPassword=trim($_POST['password1']);
        $strPassword2=trim($_POST['password2']);
        if ($strPassword==$strPassword2) {
            $strUsername=$_POST['username'];
            $strPasswordHash = password_hash($strPassword,PASSWORD_BCRYPT);

            $strSQL="UPDATE user SET passwordhash = :passwordhash WHERE username=:username";
            $stmt = $db->prepare($strSQL);
            $stmt->bindValue(':username', $strUsername);
            $stmt->bindValue(':passwordhash', $strPasswordHash);
            $stmt->execute();
            if ($stmt) {
                header("Location: index.php");
            } else {
                $boolError=true;
            }
        } else $boolError=true;
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
    <title>Passwort ändern</title>
    <style>
    .leftlabel { width: 13em;}
    input[type="text"] { width: 16em;}
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
                    <a class="nav-link" href="export.php">Export </a>
                </li>
                <li class="nav-item  active">
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
<h2>Passwort ändern</h2>

<div class="row">
<div class="col-md-7 col-lg-7">   
<br>

<?php if ($boolError): ?>
    <div class="alert alert-danger">
        <strong>Fehler!</strong> Password konnte nicht geändert werden!
        </div> <br> 
<div class="card">
<?php endif;  ?>    


    <div class="card-header">
    <h3>Dateneingabe</h3>
    </div>


    <form  id="login" action="<?=$_SERVER['PHP_SELF']?>" method="post">
        <div class="card-body">
        <label class="leftlabel">Nutzername: </label>
            <input type="text" name="username" id="username" value="<?=$strLoginName?>" readonly ><br>
        <label class="leftlabel">Passwort (mind. 8 Zeichen): </label>
            <input type="password" name="password1" id="password1" value="" minlength="8" required><br>
        <label class="leftlabel">Passwort (Wdh.): </label>
            <input type="password" name="password2" id="password2" value="" minlength="8" required><br><br>
            <label class="leftlabel">&nbsp;</label>
            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf_token']?>">
            <button type="submit" class="btn btn-primary">Passwort ändern</button>
    </div>
    </form>
</div>    
<br>



</div>
</div>
</div>

<script>
    $('#myform').submit(function(e){
        password1 = $("#password1").val();
        password2 = $("#password2").val();
        if (password1.length==0 && password2.legthn==0) {
            return true;
        }
        if (password1==password2) {
                return  true;
        } else {
            alert("Passwörter nicht gleich");
            return false;
            e.preventDefault();
        }
    });
</script>

</body>
</html>
<?php
$dbFilename="../db/locations.db";
require ("../config.php");

if ($boolComment){
   $strUsername = htmlentities(trim($_POST['comment_username']));
   $strUsername = addslashes($strUsername);
   $strComment =  htmlentities(trim($_POST['comment']));
   $strComment = addslashes($strComment);
   $id=(int)$_POST['loc_id'];

   $stmt = $db->prepare("INSERT INTO comment (loc_id,username,comment) 
             VALUES (:loc_id,:username,:comment)");
    $stmt->bindValue(':username', $strUsername);
    $stmt->bindValue(':comment', $strComment);
    $stmt->bindValue(':loc_id', $id);
    $stmt->execute();
   
    echo "ok";
}
?>
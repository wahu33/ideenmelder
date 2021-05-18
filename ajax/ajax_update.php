<?php 

$dbFilename="../db/locations.db";
require_once("../config.php");
require_once("../lib/functions.php");

$strDescription = htmlentities(trim($_POST['description']));
$strDescription = addslashes($strDescription);
$numDefect    = (isset($_POST['defect'])) ? $_POST['defect'] : 0;
$id           = (int) $_POST['loc_id'];
$filename     ="";


$boolUploadOk=false;
if ($boolUpload && isset($_FILES['uploadfile'])) {
    $uploadfile = $uploaddir . basename($_FILES['uploadfile']['name']);
    $fileinfo = @getimagesize($_FILES["uploadfile"]["tmp_name"]);
    if (!empty($fileinfo)) {
        //$info=read_gps_location($_FILES["uploadfile"]["tmp_name"]);
        $i=1;
        while (file_exists($uploadfile)) {
            $uploadfile=$uploaddir.$i."_".basename($_FILES['uploadfile']['name']);
            $i++;
        }
        if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadfile)) {
            $filename=$_FILES['uploadfile']['name'];
            $filesize=$_FILES['uploadfile']['size'];
            $filetype=$_FILES['uploadfile']['type'];
            //echo "Filetype: ".$filetype;
            $boolUploadOk = true;
        } else {
            die("Upload failed with error code " . $_FILES['file']['error']);
        }
    }
}

$stmt = $db->prepare("UPDATE location SET description= :description, defect = :defect WHERE id= :id");
             
$stmt->bindValue(':description', $strDescription);
$stmt->bindValue(':defect', $numDefect);
$stmt->bindValue(':id', $id);
$r=$stmt->execute();


// Store File Upload
if ($boolUploadOk) {
    $strSQL="INSERT INTO files (loc_id,filename,filesize,filetype) VALUES (:loc_id,:filename,:filesize,:filetype)";
    $stmt = $db->prepare($strSQL);
    $stmt->bindValue(':loc_id',$id);
    $stmt->bindValue(':filename',$filename);
    $stmt->bindValue(':filesize',$filesize);
    $stmt->bindValue(':filetype',$filetype);
    $stmt->execute();
}

$result = array(
    "id" => $id,
    "description" => stripshlashes(nl2br($strDescription)),
    "defect" => $arrDefect[$numDefect],
    "filename" => $filename,
);

echo json_encode($result);
             
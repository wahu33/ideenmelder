<?php 

$dbFilename="../db/locations.db";
require_once("../config.php");
require_once("../lib/functions.php");
require_once("../lib/geocoding.php");

$strUsername  = htmlentities(trim($_POST['username']));
$strUsername = addslashes($strUsername);
$strAge       = (isset($_POST['ext_age'])) ? $_POST['ext_age'] : "";
$strTransport = (isset($_POST['ext_transport'])) ? $_POST['ext_transport'] : "";
$strDescription = htmlentities(trim($_POST['description']));
$strDescription = addslashes($strDescription);
$numTopic     = (isset($_POST['topic'])) ? $_POST['topic'] : 1;
$numDefect    = (isset($_POST['defect'])) ? $_POST['defect'] : 0;
$numLng = $_POST['lng'];
$numLat = $_POST['lat'];
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

$stmt = $db->prepare("INSERT INTO location (username,age,transport,description,defect,topic,lng,lat) 
             VALUES (:username,:age,:transport,:description,:defect,:topic,:lng,:lat)");
             
$stmt->bindValue(':username', $strUsername);
$stmt->bindValue(':age', $strAge);
$stmt->bindValue(':transport', $strTransport);
$stmt->bindValue(':description', $strDescription);
$stmt->bindValue(':topic', $numTopic);
$stmt->bindValue(':lng', $numLng);
$stmt->bindValue(':lat', $numLat);
$stmt->bindValue(':defect', $numDefect);
$stmt->execute();

// fetch last_id - sqlite 
$strSQL="SELECT id FROM location ORDER BY id DESC limit 1";
$result = $db->query($strSQL);
if ($row = $result->fetchArray()) {
    $id = $row['id'];
}

// Write address data to table address
$data=getAddress($numLat,$numLng);
if ($data) { 
    writeAddress($db,$id,$data);
}

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

// Retrun Markertext of entry
$strSQL="SELECT loc.*,f.filename FROM location loc LEFT JOIN  files f ON loc.id=f.loc_id ORDER BY loc.id DESC limit 1";
$result = $db->query($strSQL);
if ($row = $result->fetchArray()) {
    $markerText=generate_tooltip_description($row);
    $markerText=stripcslashes($markerText);
}    



echo ($markerText);
             
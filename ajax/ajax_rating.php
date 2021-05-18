<?php
    $dbFilename="../db/locations.db";
    require_once("../config.php");

    $mode = trim($_POST['mode']);
    $id = (int)$_POST['id'];
    $value = (int)$_POST['value'];


    if ($mode=="up") {
        $db->exec("UPDATE location SET thumb_ups=thumb_ups+1 WHERE id=".$id);
        echo "success";

    } elseif ($mode=="down") {
        $db->exec("UPDATE location SET thumb_downs=thumb_ups+1 WHERE id=".$id);
        echo "success";
    } else {
        echo "error";
    };
 
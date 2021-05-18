<?php

/** -----------------------------------------------------
 *  function getAdress($lat,$lng)
 *  Reverse geocoding of address by using locationiq.com
 *  Input: lat and lng of location
 *  Returns: data-array 
 * ----------------------------------------------------- */

//$dbFilename = "../db/locations.db";
//$db = new SQLite3($dbFilename);


function getAddress($lat,$lng) {

    $key="your-key-here";
    $url="https://us1.locationiq.com/v1/reverse.php?key=".$key."&lat=".$lat."&lon=".$lng."&format=json";

    $curl = curl_init($url);

    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER    =>  true,
        CURLOPT_FOLLOWLOCATION    =>  true,
        CURLOPT_MAXREDIRS         =>  10,
        CURLOPT_TIMEOUT           =>  30,
        CURLOPT_CUSTOMREQUEST     =>  'GET',
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    $arrData = array();

    if ($err) {
        return false; //"cURL Error #:" . $err;
    } else {
        $data = json_decode($response);
    
        foreach ($data as $key=>$value) {
            if ($key=="address") {
                foreach ($value as $k=>$v) {
                    //echo $k."  ".$v."<br>";
                    $arrData[$k]=$v;
                }
            }
        };
        return $arrData;
    }
}

/** ----------------------------------------------
 * function writeAddress
 * Write data to database
 * $db - database handel
 * $id - id of location
 * $data - address data of location
 * location - address schould be an 1:1-relation
 * ----------------------------------------------- */
function writeAddress($db,$id,$data) {
    $arrKeys = array ('parking','road','house_number','industrial','neighbourhood','hamlet','suburb','postcode','city','county','country');
    $strSQL="insert into address (loc_id,parking,road,house_number,industrial,";
    $strSQL.="neighbourhood,hamlet,suburb,postcode,city,county,country) values ($id";
            foreach ($arrKeys as $key) {
                $strSQL .=  (isset($data[$key])) ? ",'".$data[$key]."'" : ",''";
            }
    $strSQL.=")";
    $db->exec($strSQL);

}

/**
 * function fillAddressTable($db,$limit)
 * $db - database handel
 * $limit - only look for these count of entries because of api restriction
 */
function fillAddressTable($db,$limit=20) {
    $arrIds = array();
    // Get all ids from address table and write to array
    $strSQL="select loc_id from address";
    $result=$db->query($strSQL);
    while ($row=$result->fetchArray()) {
        $arrIds[]=$row['loc_id'];
    }

    $counter=0;
    $strSQL="SELECT id,lat,lng FROM location";

    $arrKeys = array ('parking','road','house_number','industrial','neighbourhood','hamlet','suburb','postcode','city','county','country');

    $result=$db->query($strSQL);
    $strTable = "<table class='table table-bordered table-striped'>";
    $strTable .= "<tr><th>id</th><th>lat</th><th>lng</th>";
    foreach ($arrKeys as $key) {
        $strTable .=  "<th>".$key."</th>";
    }
    $strTable .= "</tr>";
    while ($row=$result->fetchArray()) {
        $id=$row['id'];
        if (!in_array($id,$arrIds) && $counter<$limit) {
            $counter++;
            $lat=$row['lat'];
            $lng=$row['lng'];
            $data=getAddress($lat,$lng);

            $strTable .= "<tr><td>$id</td><td>$lat</td><td>$lng</td>";
            foreach ($arrKeys as $key) {
                $strTable .=   (isset($data[$key])) ? "<td>".$data[$key]."</td>" : "<td></td>";
            }
            $strTable .=  "</tr>";

            sleep(0.5); //api restriction
            writeAddress($db,$id,$data);
        }
    }
    $strTable .=  "<table>";
    return ($counter>0) ? $strTable : "Keine neuen Adressdaten."; 
}

function cleanAddresses($db){
    $strSQL="DELETE FROM address WHERE parking='' and road='' and house_number='' 
                             and industrial='' and neighbourhood='' and hamlet='' 
                             and suburb='' and postcode='' and city='' and county=''";
   $db->query($strSQL);
}
// echo fillAddressTable($db,10);
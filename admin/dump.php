<?php

/** *****************************
 * Ideenmelder
 * Autor: Walter Hupfeld, Hamm
 * E-Mail: info@hupfeld-software.de
 * Version: 1.0
 * Datum: 18.05.2021
 ******************************** */

  session_start();
  $strLoginName=(isset($_SESSION['user'])) ? $_SESSION['user'] : "" ;
  $boolLogin = (!empty($strLoginName));
  if (!$boolLogin) {
      header("Location: login.php");
  }
  $dbFilename="../db/locations.db";
  require ("../config.php");

  // Set headers to make the browser download the results as a csv file
  header("Content-type: text/csv");
  header("Content-Disposition: attachment; filename=dump.csv");
  header("Pragma: no-cache");
  header("Expires: 0");
    
  // Query

  $strSQL="SELECT l.id as lid,l.*,adr.* 
          FROM location l LEFT JOIN address adr ON l.id=adr.loc_id ORDER BY created_at ASC";
  $query = $db->query($strSQL);      
  
  // Fetch the first row
  $row = $query->fetchArray(SQLITE3_ASSOC);
  
  // If no results are found, echo a message and stop
  if ($row == false){
      echo "No results";
      exit;
  }
  
  // Print the titles using the first line
  print_titles($row);
  // Iterate over the results and print each one in a line
  while ($row != false) {
        // Print the line
      $line = implode( ";",array_values($row));
      $line = html_entity_decode($line);
      $line = str_replace(array("\r\n", "\r", "\n"), "<br />", $line);

      echo $line . "\n";
        // Fetch the next line
      $row = $query->fetchArray(SQLITE3_ASSOC);
  }
  
  // Prints the column names
  function print_titles($row){
      echo implode(";",array_keys($row)) . "\n";
  }


?>
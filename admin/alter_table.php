<?php
  session_start();
  $strLoginName=(isset($_SESSION['user'])) ? $_SESSION['user'] : "" ;
  $boolLogin = (!empty($strLoginName));
  if (!$boolLogin) {
      header("Location: login.php");
  }

date_default_timezone_set('UTC');
const DB_FILENAME = "../db/locations.db";
$db = new SQLite3(DB_FILENAME);

$strSQL="ALTER TABLE location ADD COLUMN defect INTEGER";
$db->exec($strSQL);

$db->exec("CREATE TABLE IF NOT EXISTS user(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT,
    passwordhash TEXT,
    lastlogin TEXT DEFAULT CURRENT_TIMESTAMP,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
 )");

header("Location: configuration.php");
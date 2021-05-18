<?php

date_default_timezone_set('UTC');

$db = new SQLite3($dbFilename);

$db->exec("CREATE TABLE IF NOT EXISTS location(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username text NOT NULL DEFAULT '',
    age text NOT NULL DEFAULT '',
    transport text NOT NULL DEFAULT '',
    description text NOT NULL DEFAULT '',
    defect number,
    topic number,
    lng number,
    lat number,
    thumb_ups INTEGER DEFAULT 0,
    thumb_downs INTEGER DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("CREATE TABLE IF NOT EXISTS files(
   id INTEGER PRIMARY KEY AUTOINCREMENT,
   loc_id INTEGER,
   filename TEXT,
   filetype TEXT,
   filesize INTEGER
)");

$db->exec("CREATE TABLE IF NOT EXISTS comment(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    loc_id INTEGER,
    username TEXT,
    comment TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
 )");

$db->exec("CREATE TABLE IF NOT EXISTS user(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT,
    passwordhash TEXT,
    lastlogin TEXT DEFAULT CURRENT_TIMESTAMP,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
 )");

$db->exec("CREATE TABLE IF NOT EXISTS address(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    loc_id INTEGER,
    parking TEXT,
    road TEXT,
    house_number TEXT,
    industrial TEXT,
    neighbourhood TEXT,
    hamlet TEXT,
    suburb TEXT,
    postcode TEXT,
    city TEXT,
    county TEXT,
    country TEXT
 )");

$db->exec("CREATE TABLE IF NOT EXISTS 'config' ('key' TEXT PRIMARY KEY NOT NULL DEFAULT NULL, 'value' TEXT DEFAULT NULL);
INSERT INTO 'config' ('key','value') VALUES ('boolActive','1');
INSERT INTO 'config' ('key','value') VALUES ('boolRating','1');
INSERT INTO 'config' ('key','value') VALUES ('boolComment','1');
INSERT INTO 'config' ('key','value') VALUES ('boolUpload','1');
INSERT INTO 'config' ('key','value') VALUES ('boolUserinfo','0');
INSERT INTO 'config' ('key','value') VALUES ('boolDefect','1');
INSERT INTO 'config' ('key','value') VALUES ('uploaddir','/var/www/html/images/');
INSERT INTO 'config' ('key','value') VALUES ('title','Testversion Hamm');
INSERT INTO 'config' ('key','value') VALUES ('fileGeojson','geojson/hamm.geojson');
INSERT INTO 'config' ('key','value') VALUES ('InfoLat','51.66');
INSERT INTO 'config' ('key','value') VALUES ('InfoLng','7.825');
INSERT INTO 'config' ('key','value') VALUES ('zoom','12');
INSERT INTO 'config' ('key','value') VALUES ('logo','css/logo.png');
INSERT INTO 'config' ('key','value') VALUES ('contactEmail','info@radwege-hamm.de');
INSERT INTO 'config' ('key','value') VALUES ('impressum','Walter Hupfeld
Bankerheide 2
59065 Hamm');
INSERT INTO 'config' ('key','value') VALUES ('url','https://www.radwege-hamm.de');
INSERT INTO 'config' ('key','value') VALUES ('UrlBez','Homepage Radwege Hamm');
INSERT INTO 'config' ('key','value') VALUES ('IntroText','Hier können Sie uns Hinweise auf Verbesserungen der Verkehrsinfrastruktur in der Stadt Mülheim an der Ruhr vorschlagen.

Klicken Sie dazu auf entsprechenden Ort auf der Karte und geben Sie im Dialog ihre Anmerkungen ein.
Den Marker können sie solange auf die richtige Stelle verschieben, bis die Eingabe abgeschlossen ist.

Vielen Dank für Ihre Unterstützung.');
INSERT INTO 'config' ('key','value') VALUES ('stadt','Hamm');
");


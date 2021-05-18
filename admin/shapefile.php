<?php


// Query
$dbFilename="../db/locations.db";
  require ("../config.php");


$result = $db->query("SELECT * FROM location ORDER BY created_at ASC");


$coords_array = array ();

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
   $coords_array[]=$row;
}

// If no results are found, echo a message and stop
//if ($coords_array == false) { echo "No results"; exit; }

unlink ("shape/ideenmelder.shp");
unlink ("shape/ideenmelder.dbf");
unlink ("shape/ideenmelder.shx");
//unlink ("shape/ideenmelder.dbt");

require_once('../vendor/Shapefile/ShapefileAutoloader.php');
Shapefile\ShapefileAutoloader::register();

// Import classes
use Shapefile\Shapefile;
use Shapefile\ShapefileException;
use Shapefile\ShapefileWriter;
use Shapefile\Geometry\Point;

try {
    // Open Shapefile
    $Shapefile = new ShapefileWriter('shape/ideenmelder.shp');
    
    // Set shape type
    $Shapefile->setShapeType(Shapefile::SHAPE_TYPE_POINT);
    
    // Create field structure
    $Shapefile->addNumericField('ID', 10);
    $Shapefile->addCharField('DESC');

    $Shapefile->addCharField('TOPIC',20);
    $Shapefile->addCharField('DEFECT',60);
    
    foreach ($coords_array as  $coords) {
        //echo "Coords ".$coords['id'].":";print_r($coords);echo "<hr>";
        // Create a Point Geometry
        $lat=$coords['lat'];
        $lon=$coords['lng'];
        $Point = new Point($lon,$lat);
        // Set its data
        $Point->setData('ID', $coords['id']);
        $Point->setData('TOPIC', $arrTopic[$coords['topic']]);
        $Point->setData('DESC', "Point number ".$coords['id'].":".$coords['description']);
        if (!empty($coords['defect'])) {
            $Point->setData('DEFECT', $arrDefect[$coords['defect']]);
        } else {
            $Point->setData('DEFECT',"Keine Angabe");
        }
        // Write the record to the Shapefile
        $Shapefile->writeRecord($Point);
    }
    
    // Finalize and close files to use them
    $Shapefile = null;
   

} catch (ShapefileException $e) {
    // Print detailed error information
    echo "Error Type: " . $e->getErrorType()
        . "\nMessage: " . $e->getMessage()
        . "\nDetails: " . $e->getDetails();
}



// Get real path for our folder
$rootPath = realpath('shape');
// Initialize archive object
$zip = new ZipArchive();
$filename="shapefile.zip";
$zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}
// Zip archive will be created only after closing object
$zip->close();

$path=realPath("./");
// http headers for zip downloads
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$filename."\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($path."/".$filename));
ob_end_flush();
@readfile($path."/".$filename);
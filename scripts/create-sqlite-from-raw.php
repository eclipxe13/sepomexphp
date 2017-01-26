<?php

/**
 * This script is used to create the database using PDO and sqlite
 */

require_once __DIR__ . '/../vendor/autoload.php';

// escape the global scope
call_user_func(function() {

    $path = realpath(__DIR__ . '/../assets');
    // create the path if this soes not exists
    if (! is_dir($path)) {
        mkdir($path, 0755, true);
    }
    // files
    $dbfile = $path . '/sepomex.db';
    $rawfile = $path . '/sepomex.txt';

    // raw file
    if (!file_exists($rawfile)) {
        $sourceurl = "http://www.correosdemexico.gob.mx/datosabiertos/cp/cpdescarga.txt";
        echo "File $rawfile does not exists, will be downloaded from $sourceurl\n";
        copy($sourceurl, $rawfile);
    }

    // touch the dbfile if not exists
    if (!file_exists($dbfile)) {
        touch($dbfile);
    }

    // create the pdo object
    $pdo = new \PDO("sqlite:" . $dbfile, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    // create the importer class
    $importer = new \SepomexPhp\Importer\PdoImporter($pdo);

    // follow this steps
    $importer->createStruct();
    $importer->importRawTxt($rawfile);
    $importer->populateStates();
    $importer->populateDistricts();
    $importer->populateCities();
    $importer->populateZipCodes();
    $importer->populateLocationTypes();
    $importer->populateLocations();
    $importer->populateLocationZipCodes();
    $importer->dropRawTable();
});

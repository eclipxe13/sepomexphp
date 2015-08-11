<?php
/**
 * This script is a command line tool to get information about a zipcode
 * It uses the database on tests/assets/sepomex.db
 * This database is not distributable but you can create it with create-sqlite-from-raw.php
 * Usage: zipcode-infp.php zipcode
 */
require_once __DIR__ . '/../vendor/autoload.php';

// escape the global scope

if (2 !== $argc) {
    echo "Usage: ", $argv[0], " zipcode\n";
    exit;
}

call_user_func(function() use ($argv) {

    // set the database location
    $dbfile = __DIR__ . '/../tests/assets/sepomex.db';
    // create the PDO Object
    $pdo = new PDO("sqlite:" . $dbfile, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    // create the gateway
    $gateway = new SepomexPhp\PdoGateway\Gateway($pdo);
    // create the SepomexPhp Object
    $sepomex = new \SepomexPhp\SepomexPhp($gateway);

    // query a zip code
    $zipcode = $sepomex->getZipCodeData((int) $argv[1]);
    if (null === $zipcode) {
        echo "Not found: ", $argv[1], "\n";
        exit;
    }

    // display information
    echo "      ZipCode: ", sprintf("%05d", $zipcode->zipcode), "\n";
    echo "     District: ", $zipcode->district->name, "\n";
    echo "        State: ", $zipcode->state->name, "\n";
    echo "    Locations: ", count($zipcode->locations), "\n";
    foreach($zipcode->locations as $location) {
        echo "               ", $location->getFullName();
        echo ($location->city) ? ", City: " . $location->city->name : "";
        echo "\n";
    }
});




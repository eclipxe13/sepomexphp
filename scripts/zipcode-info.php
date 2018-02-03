<?php
/**
 * This script is a command line tool to get information about a zipcode
 * It uses the database on assets/sepomex.db
 * This database is not distributable but you can create it with create-sqlite-from-raw.php
 * Usage: zipcode-infp.php zipcode
 */
require_once __DIR__ . '/../vendor/autoload.php';

// escape the global scope
call_user_func(function ($argv) {
    // exit if no arguments
    if (2 !== count($argv)) {
        echo 'Usage: ', $argv[0], " zipcode\n";
        return;
    }

    // set the database location
    $dbfile = __DIR__ . '/../assets/sepomex.db';
    // create the PDO Object
    $pdo = new PDO('sqlite:' . $dbfile, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    // create the gateway
    $gateway = new SepomexPhp\PdoGateway\Gateway($pdo);
    // create the SepomexPhp Object
    $sepomex = new \SepomexPhp\SepomexPhp($gateway);

    // query a zip code
    $zipcode = $sepomex->getZipCodeData((int) $argv[1]);
    if (null === $zipcode) {
        echo 'Not found: ', $argv[1], "\n";
        return;
    }
    $locations = $zipcode->locations();
    $cities = $locations->cities();
    $citiesCount = $cities->count();

    // display information
    echo '      ZipCode: ', $zipcode->format(), "\n";
    echo '     District: ', $zipcode->district()->name(), "\n";
    echo '        State: ', $zipcode->state()->name(), "\n";
    if ($citiesCount > 1) {
        echo '       Cities: ', $citiesCount, "\n";
        foreach ($cities as $city) {
            echo '               ', $city->name(), "\n";
        }
    } else {
        echo '         City: ', ($citiesCount > 0) ? $cities->byIndex(0)->name() : '(Ninguna)', "\n";
    }
    echo '    Locations: ', $locations->count() ? : '(Ninguna)', "\n";
    foreach ($locations as $location) {
        echo '               ', $location->getFullName(), "\n";
    }
}, $argv);

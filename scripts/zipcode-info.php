<?php

declare(strict_types=1);

/**
 * This script is a command line tool to get information about a zipcode
 * It uses the database on assets/sepomex.db
 * This database is not distributable but you can create it with create-sqlite-from-raw.php
 *
 * Usage: zipcode-info.php zipcode
 */

use Eclipxe\SepomexPhp\PdoGateway\Gateway;
use Eclipxe\SepomexPhp\SepomexPhp;

require_once __DIR__ . '/../vendor/autoload.php';

// escape the global scope
$returnValue = call_user_func(function (array $argv) {
    // exit if no arguments
    if (2 !== count($argv)) {
        echo 'Usage: ', $argv[0], " zipcode\n";
        return 1;
    }

    try {
        // set the database location
        $dbfile = __DIR__ . '/../assets/sepomex.db';
        // create the PDO Object
        $pdo = new PDO('sqlite:' . $dbfile, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        // create the gateway
        $gateway = new Gateway($pdo);
        // create the SepomexPhp Object
        $sepomex = new SepomexPhp($gateway);

        // query a zip code
        $zipcode = $sepomex->getZipCodeData($argv[1]);
        if (null === $zipcode) {
            echo 'Not found: ', $argv[1], "\n";
            return 1;
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
        return 0;
    } catch (Throwable $exception) {
        file_put_contents('php://stderr', $exception->getMessage(), FILE_APPEND);
        return 1;
    }
}, $argv);

exit($returnValue);

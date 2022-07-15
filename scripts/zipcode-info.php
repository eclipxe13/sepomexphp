<?php

/**
 * This script is a command line tool to get information about a zipcode
 * It uses the database on assets/sepomex.db
 * This database is not distributable, but you can create it with create-sqlite-from-raw.php
 *
 * Usage: zipcode-info.php zipcode
 */

declare(strict_types=1);

use Eclipxe\SepomexPhp\PdoGateway\Gateway;
use Eclipxe\SepomexPhp\SepomexPhp;

require_once __DIR__ . '/../vendor/autoload.php';

// escape the global scope
$returnValue = call_user_func(function (string $command, string $zipcodeInput = '', string ...$otherArguments) {
    // exit if no arguments
    if ('' === $zipcodeInput || [] !== $otherArguments) {
        echo 'Usage: ', $command, ' zipcode', PHP_EOL;
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
        $zipcode = $sepomex->getZipCodeData($zipcodeInput);
        if (null === $zipcode) {
            echo 'Not found: ', $zipcodeInput, PHP_EOL;
            return 1;
        }
        $locations = $zipcode->locations();
        $cities = $locations->cities();
        $citiesCount = $cities->count();

        // display information
        echo '      ZipCode: ', $zipcode->format(), PHP_EOL;
        echo '     District: ', $zipcode->district()->name(), PHP_EOL;
        echo '        State: ', $zipcode->state()->name(), PHP_EOL;
        if ($citiesCount > 1) {
            echo '       Cities: ', $citiesCount, PHP_EOL;
            foreach ($cities as $city) {
                echo '               ', $city->name(), PHP_EOL;
            }
        } else {
            echo '         City: ', ($citiesCount > 0) ? $cities->byIndex(0)->name() : '(Ninguna)', PHP_EOL;
        }
        echo '    Locations: ', $locations->count() ?: '(Ninguna)', PHP_EOL;
        /** @var \Eclipxe\SepomexPhp\Data\Location $location */
        foreach ($locations as $location) {
            echo '               ', $location->getFullName(), PHP_EOL;
        }
        return 0;
    } catch (Throwable $exception) {
        file_put_contents('php://stderr', $exception->getMessage() . PHP_EOL, FILE_APPEND);
        return 1;
    }
}, ...$argv);

exit($returnValue);

<?php

/**
 * This script is a command line tool to get information about a zipcode
 * It uses the database on assets/sepomex.db
 * This database is not distributable, but you can create it with create-sqlite-from-raw.php
 *
 * Usage: zipcode-info.php zipcode
 */

declare(strict_types=1);

use Eclipxe\SepomexPhp\SepomexPhp;

require_once __DIR__ . '/../vendor/autoload.php';

exit(call_user_func(function (string $command, string $zipcodeInput = '', string ...$otherArguments) {
    // exit if no arguments

    $askForHelp = in_array($zipcodeInput, ['--help', '-h', 'help']);
    if ($askForHelp || '' === $zipcodeInput || [] !== $otherArguments) {
        echo 'Usage: ', basename($command), ' zipcode', PHP_EOL;
        return $askForHelp ? 0 : 1;
    }

    try {
        // set the database location
        $dbFile = __DIR__ . '/../assets/sepomex.db';
        // create the SepomexPhp Object
        $sepomex = SepomexPhp::createForDatabaseFile($dbFile);

        // query a zip code
        $zipcode = $sepomex->getZipCodeData($zipcodeInput);
        if (null === $zipcode) {
            throw new Exception(sprintf('Not found: %s', $zipcodeInput));
        }

        $locations = $zipcode->locations;
        $cities = $locations->cities;
        $citiesCount = $cities->count();

        // create information summary
        /** @var array<array{string, string}> $table */
        $table = [
            ['ZipCode', $zipcode->formatted],
            ['District', $zipcode->district->name],
            ['State', $zipcode->state->name],
        ];

        if ($citiesCount > 1) {
            $table[] = ['Cities', sprintf('%d cities', $citiesCount)];
            foreach ($cities as $city) {
                $table[] = ['', $city->name];
            }
        } elseif (1 === $citiesCount) {
            $table[] = ['City', $cities->first()->name];
        } else {
            $table[] = ['City', '(none)'];
        }

        $locationsCount = $locations->count();
        if ($locationsCount > 1) {
            $table[] = ['Locations', sprintf('%d locations', $locationsCount)];
            foreach ($locations as $location) {
                $table[] = ['', sprintf('%s (%s)', $location->name, $location->type->name)];
            }
        } elseif (1 === $locationsCount) {
            $location = $locations->first();
            $table[] = ['Location', sprintf('%s (%s)', $location->name, $location->type->name)];
        } else {
            $table[] = ['Locations', '(none)'];
        }

        // print table
        $firstColumnLength = max(...array_map(fn (array $row): int => mb_strlen($row[0]), $table)) + 3;
        foreach ($table as $row) {
            $text = '' !== $row[0] ? sprintf('%s: ', $row[0]) : '';
            echo str_pad($text, $firstColumnLength, ' ', STR_PAD_LEFT), $row[1], PHP_EOL;
        }

        return 0;
    } catch (Throwable $exception) {
        file_put_contents('php://stderr', $exception->getMessage() . PHP_EOL, FILE_APPEND);
        return 1;
    }
}, ...$argv));

<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Tests\Importer;

use Eclipxe\SepomexPhp\Importer\PdoImporter;
use Eclipxe\SepomexPhp\Tests\TestCase;

final class PdoImporterTest extends TestCase
{
    public function testImportProcess(): void
    {
        // check if source file exists
        $rawFile = $this->filePath('rawextract.txt');
        $this->assertFileExists($rawFile);

        // create the importer with struct since this is an empty sqlite database
        $pdo = $this->pdo(':memory:');
        $importer = new PdoImporter($pdo);
        $importer->createStruct();

        // do the import process
        $importer->importRawTxt($rawFile);
        $this->assertSame(1500, $this->queryOne('select count(*) from raw;'));

        $importer->populateStates();
        $this->assertSame(15, $this->queryOne('select count(*) from states;'));

        $importer->populateDistricts();
        $this->assertSame(46, $this->queryOne('select count(*) from districts;'));

        $importer->populateCities();
        $this->assertSame(9, $this->queryOne('select count(*) from cities;'));

        $importer->populateZipCodes();
        $this->assertSame(327, $this->queryOne('select count(*) from zipcodes;'));

        $importer->populateLocationTypes();
        $this->assertSame(20, $this->queryOne('select count(*) from locationtypes;'));

        $importer->populateLocations();
        $this->assertSame(1492, $this->queryOne('select count(*) from locations;'));

        $importer->populateLocationZipCodes();
        $this->assertSame(1498, $this->queryOne('select count(*) from locationzipcodes;'));

        $importer->clearRawTable();
        $this->assertSame(0, $this->queryOne('select count(*) from raw;'));
    }

    public function testImportAllInOne(): void
    {
        // check if source file exists
        $rawFile = $this->filePath('rawextract.txt');
        $this->assertFileExists($rawFile);

        // create the importer with struct since this is an empty sqlite database
        $pdo = $this->pdo(':memory:');
        $importer = new PdoImporter($pdo);
        $importer->createStruct();

        // do the import process in one line
        $importer->import($rawFile);

        // perform all the checks
        $this->assertSame(0, $this->queryOne('select count(*) from raw;'));
        $this->assertSame(15, $this->queryOne('select count(*) from states;'));
        $this->assertSame(1, $this->queryOne("select count(*) from states where name = 'Coahuila';"));
        $this->assertSame(46, $this->queryOne('select count(*) from districts;'));
        $this->assertSame(9, $this->queryOne('select count(*) from cities;'));
        $this->assertSame(327, $this->queryOne('select count(*) from zipcodes;'));
        $this->assertSame(20, $this->queryOne('select count(*) from locationtypes;'));
        $this->assertSame(1492, $this->queryOne('select count(*) from locations;'));
        $this->assertSame(1498, $this->queryOne('select count(*) from locationzipcodes;'));
    }
}

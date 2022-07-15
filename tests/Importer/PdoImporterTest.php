<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Tests\Importer;

use Eclipxe\SepomexPhp\Importer\PdoImporter;
use Eclipxe\SepomexPhp\Tests\TestCase;

class PdoImporterTest extends TestCase
{
    public function testImportProcess()
    {
        // check if source file exists
        $rawfile = $this->filePath('rawextract.txt');
        $this->assertFileExists($rawfile);

        // create the importer with struct since this is an empty sqlite database
        $pdo = $this->pdo(':memory:');
        $importer = new PdoImporter($pdo);
        $importer->createStruct();

        // do the import process
        $importer->importRawTxt($rawfile);
        $this->assertEquals(1500, $this->queryOne('select count(*) from raw;'));

        $importer->populateStates();
        $this->assertEquals(15, $this->queryOne('select count(*) from states;'));

        $importer->populateDistricts();
        $this->assertEquals(46, $this->queryOne('select count(*) from districts;'));

        $importer->populateCities();
        $this->assertEquals(9, $this->queryOne('select count(*) from cities;'));

        $importer->populateZipCodes();
        $this->assertEquals(327, $this->queryOne('select count(*) from zipcodes;'));

        $importer->populateLocationTypes();
        $this->assertEquals(20, $this->queryOne('select count(*) from locationtypes;'));

        $importer->populateLocations();
        $this->assertEquals(1492, $this->queryOne('select count(*) from locations;'));

        $importer->populateLocationZipCodes();
        $this->assertEquals(1498, $this->queryOne('select count(*) from locationzipcodes;'));

        $importer->clearRawTable();
        $this->assertEquals(0, $this->queryOne('select count(*) from raw;'));
    }

    public function testImportAllInOne()
    {
        // check if source file exists
        $rawfile = $this->filePath('rawextract.txt');
        $this->assertFileExists($rawfile);

        // create the importer with struct since this is an empty sqlite database
        $pdo = $this->pdo(':memory:');
        $importer = new PdoImporter($pdo);
        $importer->createStruct();

        // do the import process in one line
        $statesRename = $importer->commonSatesRename();
        $importer->import($rawfile, $statesRename);

        // perform all the checks
        $this->assertEquals(0, $this->queryOne('select count(*) from raw;'));
        $this->assertEquals(15, $this->queryOne('select count(*) from states;'));
        $this->assertEquals(1, $this->queryOne("select count(*) from states where name = 'Coahuila';"));
        $this->assertEquals(46, $this->queryOne('select count(*) from districts;'));
        $this->assertEquals(9, $this->queryOne('select count(*) from cities;'));
        $this->assertEquals(327, $this->queryOne('select count(*) from zipcodes;'));
        $this->assertEquals(20, $this->queryOne('select count(*) from locationtypes;'));
        $this->assertEquals(1492, $this->queryOne('select count(*) from locations;'));
        $this->assertEquals(1498, $this->queryOne('select count(*) from locationzipcodes;'));
    }
}

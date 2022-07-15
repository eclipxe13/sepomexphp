<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Importer;

use PDO;
use PDOStatement;
use RuntimeException;
use SplFileObject;

/**
 * Import the sepomex raw file (iso-8859-1 encoded)
 * @package Eclipxe\SepomexPhp\Importer
 */
class PdoImporter
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retrieve a list of common states renames, like 'Veracruz de Ignacio de la Llave' to 'Veracruz'
     *
     * @return array<string, string>
     */
    public static function commonStatesRename(): array
    {
        return [
            'Coahuila de Zaragoza' => 'Coahuila',
            'Michoacán de Ocampo' => 'Michoacán',
            'Veracruz de Ignacio de la Llave' => 'Veracruz',
            // 'México' => 'Estado de México',
        ];
    }

    /**
     * Do the importation process from a raw file.
     * It is expected that the data structure is already created.
     *
     * @param string $rawfile
     * @param array<string, string>|null $statesRename if null will use the common set of renames
     * @see commonStatesRename
     */
    public function import(string $rawfile, array $statesRename = null): void
    {
        if (null === $statesRename) {
            $statesRename = $this->commonStatesRename();
        }
        $this->importRawTxt($rawfile);
        $this->populateStates();
        $this->renameStates($statesRename);
        $this->populateDistricts();
        $this->populateCities();
        $this->populateZipCodes();
        $this->populateLocationTypes();
        $this->populateLocations();
        $this->populateLocationZipCodes();
        $this->clearRawTable();
    }

    public function createStruct(): void
    {
        $commands = [
            // raw
            'DROP TABLE IF EXISTS raw;',
            'CREATE TABLE raw (d_codigo text, d_asenta text, d_tipo_asenta text, d_mnpio text, d_estado text,'
                . ' d_ciudad text, d_cp text, c_estado text, c_oficina text, c_cp text, c_tipo_asenta text,'
                . ' c_mnpio text, id_asenta_cpcons text, d_zona text, c_cve_ciudad text);',
            // states
            'DROP TABLE IF EXISTS states;',
            'CREATE TABLE states (id integer primary key not null, name text not null);',
            // districts (autonumeric)
            'DROP TABLE IF EXISTS districts;',
            'CREATE TABLE districts (id integer primary key autoincrement not null, idstate integer not null,'
            . ' name text not null, idraw text);',
            // cities (autonumeric)
            'DROP TABLE IF EXISTS cities;',
            'CREATE TABLE cities (id integer primary key autoincrement not null, idstate integer not null,'
            . ' name text not null, idraw text);',
            // locationtypes
            'DROP TABLE IF EXISTS locationtypes;',
            'CREATE TABLE locationtypes (id integer primary key not null, name text not null);',
            // locations
            'DROP TABLE IF EXISTS locations;',
            'CREATE TABLE locations (id integer primary key autoincrement not null, idlocationtype integer not null,'
            . ' iddistrict integer not null, idcity integer default null, name text not null);',
            // zipcodes
            'DROP TABLE IF EXISTS zipcodes;',
            'CREATE TABLE zipcodes (id integer primary key not null, iddistrict int not null);',
            // locationzipcodes
            'DROP TABLE IF EXISTS locationzipcodes;',
            'CREATE TABLE locationzipcodes (idlocation integer not null, zipcode integer not null,'
            . ' primary key(idlocation, zipcode));',
        ];
        $this->execute(...$commands);
    }

    public function importRawTxt(string $filename): void
    {
        if (! file_exists($filename) || ! is_readable($filename)) {
            throw new RuntimeException("File $filename not found or not readable");
        }
        $sqlInsert = 'INSERT INTO raw VALUES (' . trim(str_repeat('?,', 15), ',') . ');';
        $stmt = $this->pdo->prepare($sqlInsert);
        $this->pdo->beginTransaction();
        $this->pdo->exec('DELETE FROM raw');
        $source = new SplFileObject($filename, 'r');
        foreach ($source as $i => $line) {
            // discard first lines
            if ($i < 2 || is_array($line) || ! $line) {
                continue;
            }
            $values = explode('|', strval(iconv('iso-8859-1', 'utf-8', $line)));
            $stmt->execute($values);
        }
        $this->pdo->commit();
    }

    public function populateStates(): void
    {
        $commands = [
            'DELETE FROM states;',
            'INSERT INTO states SELECT DISTINCT CAST(c_estado AS INTEGER) as id, d_estado as name'
            . ' FROM raw ORDER BY c_estado;',
        ];
        $this->execute(...$commands);
    }

    /**
     * @param array<string, string> $names
     */
    public function renameStates(array $names): void
    {
        if (0 === count($names)) {
            return;
        }
        $sql = 'UPDATE states SET name = :newname WHERE (name = :oldname);';
        $stmt = $this->pdo->prepare($sql);
        foreach ($names as $oldname => $newname) {
            $stmt->execute(['oldname' => $oldname, 'newname' => $newname]);
        }
    }

    public function populateDistricts(): void
    {
        $commands = [
            'DELETE FROM districts;',
            'INSERT INTO districts SELECT DISTINCT null as id, CAST(c_estado AS INTEGER) as idstate, d_mnpio as name,'
            . ' CAST(c_mnpio AS INTEGER) as idraw FROM raw ORDER BY c_estado, c_mnpio;',
        ];
        $this->execute(...$commands);
    }

    public function populateCities(): void
    {
        $commands = [
            'DELETE FROM cities;',
            'INSERT INTO cities SELECT DISTINCT null as id, CAST(c_estado AS INTEGER) as idstate, d_ciudad as name,'
            . ' CAST(c_cve_ciudad AS INTEGER) as idraw FROM raw WHERE (d_ciudad <> "")'
            . ' ORDER BY c_estado, c_cve_ciudad;',
        ];
        $this->execute(...$commands);
    }

    public function populateLocationTypes(): void
    {
        $commands = [
            'DELETE FROM locationtypes;',
            'INSERT INTO locationtypes SELECT DISTINCT CAST(c_tipo_asenta AS INTEGER) AS id, d_tipo_asenta AS name'
            . ' FROM raw ORDER BY c_tipo_asenta;',
        ];
        $this->execute(...$commands);
    }

    public function populateLocations(): void
    {
        $commands = [
            'DELETE FROM locations;',
            'INSERT INTO locations '
            . ' SELECT DISTINCT NULL AS id, t.id as idlocationtype, d.id AS iddistrict,'
            . ' c.id AS idcity, d_asenta AS name'
            . ' FROM raw AS r'
            . ' INNER JOIN locationtypes as t ON (t.name = r.d_tipo_asenta)'
            . ' INNER JOIN districts as d'
            . ' ON (d.idraw = CAST(c_mnpio AS INTEGER) AND d.idstate = CAST(c_estado AS INTEGER))'
            . ' LEFT JOIN cities as c'
            . ' ON (c.idraw = CAST(c_cve_ciudad AS INTEGER) AND c.idstate = CAST(c_estado AS INTEGER))'
            . ';',
        ];
        $this->execute(...$commands);
    }

    public function populateZipCodes(): void
    {
        $commands = [
            'DELETE FROM zipcodes;',
            'INSERT INTO zipcodes'
            . ' SELECT DISTINCT CAST(d_codigo AS INTEGER) AS id, d.id AS iddistrict'
            . ' FROM raw AS r'
            . ' INNER JOIN districts AS d'
            . ' ON (d.idraw = CAST(c_mnpio AS INTEGER) AND d.idstate = CAST(c_estado AS INTEGER))'
            . ';',
        ];
        $this->execute(...$commands);
    }

    public function populateLocationZipCodes(): void
    {
        $commands = [
            'DELETE FROM locationzipcodes;',
            'INSERT INTO locationzipcodes'
            . ' SELECT DISTINCT l.id AS idlocation, CAST(d_codigo AS INTEGER) AS zipcode'
            . ' FROM raw AS r'
            . ' INNER JOIN locationtypes AS t ON (t.name = r.d_tipo_asenta)'
            . ' INNER JOIN districts AS d'
            . ' ON (d.idraw = CAST(c_mnpio AS INTEGER) AND d.idstate = CAST(c_estado AS INTEGER))'
            . ' INNER JOIN locations AS l ON (t.id = l.idlocationtype AND d.id = l.iddistrict AND l.name = r.d_asenta)'
            . ';',
        ];
        $this->execute(...$commands);
    }

    public function clearRawTable(): void
    {
        $this->execute('DELETE FROM raw;');
    }

    /**
     * @param string|PDOStatement ...$commands
     * @return void
     */
    protected function execute(...$commands): void
    {
        foreach ($commands as $command) {
            if (is_string($command)) {
                $this->pdo->exec($command);
            } elseif ($command instanceof PDOStatement) {
                $command->execute();
            }
        }
    }
}

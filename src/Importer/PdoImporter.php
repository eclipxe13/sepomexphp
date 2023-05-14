<?php

/** @noinspection SqlWithoutWhere */

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Importer;

use PDO;
use PDOStatement;
use RuntimeException;
use SplFileObject;

/**
 * Import the sepomex raw file (iso-8859-1 encoded)
 */
class PdoImporter
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * Do the importation process from a raw file.
     * It is expected that the data structure is already created.
     */
    public function import(string $filename, ?RenameRules $renameStatesRules = null): void
    {
        $this->importRawTxt($filename);
        $this->populateStates();
        $this->renameStates($renameStatesRules ?? RenameRules::createDefault());
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
        $this->execute(
            // raw
            'DROP TABLE IF EXISTS raw;',
            <<< SQL
                CREATE TABLE raw (
                    d_codigo text,
                    d_asenta text,
                    d_tipo_asenta text,
                    d_mnpio text,
                    d_estado text,
                    d_ciudad text,
                    d_cp text,
                    c_estado text,
                    c_oficina text,
                    c_cp text,
                    c_tipo_asenta text,
                    c_mnpio text,
                    id_asenta_cpcons text,
                    d_zona text,
                    c_cve_ciudad text
                );
                SQL,
            // states
            'DROP TABLE IF EXISTS states;',
            <<< SQL
                CREATE TABLE states (
                    id integer primary key not null,
                    name text not null
                );
                SQL,
            // districts (autoincrement)
            'DROP TABLE IF EXISTS districts;',
            <<< SQL
                CREATE TABLE districts (
                    id integer primary key autoincrement not null,
                    idstate integer not null,
                    name text not null,
                    idraw text
                );
                SQL, // cities (autoincrement)
            'DROP TABLE IF EXISTS cities;',
            <<< SQL
                CREATE TABLE cities (
                    id integer primary key autoincrement not null,
                    idstate integer not null,
                    name text not null,
                    idraw text
                );
                SQL,
            // locationtypes
            'DROP TABLE IF EXISTS locationtypes;',
            <<< SQL
                CREATE TABLE locationtypes (
                    id integer primary key not null,
                    name text not null
                );
                SQL,
            // locations
            'DROP TABLE IF EXISTS locations;',
            <<< SQL
                CREATE TABLE locations (
                    id integer primary key autoincrement not null,
                    idlocationtype integer not null,
                    iddistrict integer not null,
                    idcity integer default null,
                    name text not null
                );
                SQL,
            // zipcodes
            'DROP TABLE IF EXISTS zipcodes;',
            <<< SQL
                CREATE TABLE zipcodes (
                    id integer primary key not null,
                    iddistrict int not null
                );
                SQL,
            // locationzipcodes
            'DROP TABLE IF EXISTS locationzipcodes;',
            <<< SQL
                CREATE TABLE locationzipcodes (
                    idlocation integer not null,
                    zipcode integer not null,
                    primary key(idlocation, zipcode)
                );
                SQL,
        );
    }

    public function importRawTxt(string $filename): void
    {
        if (! file_exists($filename) || ! is_readable($filename)) {
            throw new RuntimeException("File $filename not found or not readable");
        }
        /** @noinspection SqlInsertValues */
        $sqlInsert = 'INSERT INTO raw VALUES (' . trim(str_repeat('?,', 15), ',') . ');';
        $stmtInsert = $this->pdo->prepare($sqlInsert);
        $this->pdo->beginTransaction();
        $this->pdo->exec('DELETE FROM raw;');
        $source = new SplFileObject($filename, 'r');
        foreach ($source as $i => $line) {
            // discard first lines
            if ($i < 2 || is_array($line) || ! $line) {
                continue;
            }
            $values = explode('|', strval(iconv('iso-8859-1', 'utf-8', $line)));
            $stmtInsert->execute($values);
        }
        $this->pdo->commit();
    }

    public function populateStates(): void
    {
        $this->execute(
            'DELETE FROM states;',
            <<< SQL
                INSERT INTO states (id, name)
                SELECT DISTINCT CAST(c_estado AS INTEGER) as id, d_estado as name
                FROM raw
                ORDER BY c_estado;
                SQL,
        );
    }

    public function renameStates(RenameRules $renameStatesRules): void
    {
        $sql = 'UPDATE states SET name = :new WHERE (name = :old);';
        $stmt = $this->pdo->prepare($sql);
        foreach ($renameStatesRules->rulesAsNames() as $old => $new) {
            $stmt->execute(['old' => $old, 'new' => $new]);
        }
    }

    public function populateDistricts(): void
    {
        $this->execute(
            'DELETE FROM districts;',
            <<< SQL
                INSERT INTO districts (idstate, name, idraw)
                SELECT DISTINCT CAST(c_estado AS INTEGER) as idstate, d_mnpio as name, CAST(c_mnpio AS INTEGER) as idraw
                FROM raw
                ORDER BY c_estado, c_mnpio;
                SQL,
        );
    }

    public function populateCities(): void
    {
        $this->execute(
            'DELETE FROM cities;',
            <<< SQL
                INSERT INTO cities (idstate, name, idraw)
                SELECT DISTINCT CAST(c_estado AS INTEGER) as idstate, d_ciudad as name,
                                CAST(c_cve_ciudad AS INTEGER) as idraw
                FROM raw
                WHERE (d_ciudad <> '')
                ORDER BY c_estado, c_cve_ciudad;
                SQL,
        );
    }

    public function populateLocationTypes(): void
    {
        $this->execute(
            'DELETE FROM locationtypes;',
            <<< SQL
                INSERT INTO locationtypes (id, name)
                SELECT DISTINCT CAST(c_tipo_asenta AS INTEGER) AS id, d_tipo_asenta AS name
                FROM raw ORDER BY c_tipo_asenta;
                SQL,
        );
    }

    public function populateLocations(): void
    {
        $this->execute(
            'DELETE FROM locations;',
            <<< SQL
                INSERT INTO locations (idlocationtype, iddistrict, idcity, name)
                SELECT DISTINCT t.id as idlocationtype, d.id AS iddistrict, c.id AS idcity, d_asenta AS name
                FROM raw AS r
                INNER JOIN locationtypes as t ON (t.name = r.d_tipo_asenta)
                INNER JOIN districts as d
                    ON (d.idraw = CAST(c_mnpio AS INTEGER) AND d.idstate = CAST(c_estado AS INTEGER))
                LEFT JOIN cities as c
                    ON (c.idraw = CAST(c_cve_ciudad AS INTEGER) AND c.idstate = CAST(c_estado AS INTEGER));
                SQL,
        );
    }

    public function populateZipCodes(): void
    {
        $this->execute(
            'DELETE FROM zipcodes;',
            <<< SQL
                INSERT INTO zipcodes (id, iddistrict)
                SELECT DISTINCT CAST(d_codigo AS INTEGER) AS id, d.id AS iddistrict
                FROM raw AS r
                INNER JOIN districts AS d
                    ON (d.idraw = CAST(c_mnpio AS INTEGER) AND d.idstate = CAST(c_estado AS INTEGER));
                SQL,
        );
    }

    public function populateLocationZipCodes(): void
    {
        $this->execute(
            'DELETE FROM locationzipcodes;',
            <<< SQL
                INSERT INTO locationzipcodes (idlocation, zipcode)
                SELECT DISTINCT l.id AS idlocation, CAST(d_codigo AS INTEGER) AS zipcode
                FROM raw AS r
                INNER JOIN locationtypes AS t ON (t.name = r.d_tipo_asenta)
                INNER JOIN districts AS d
                    ON (d.idraw = CAST(c_mnpio AS INTEGER) AND d.idstate = CAST(c_estado AS INTEGER))
                INNER JOIN locations AS l
                    ON (t.id = l.idlocationtype AND d.id = l.iddistrict AND l.name = r.d_asenta);
                SQL,
        );
    }

    public function clearRawTable(): void
    {
        $this->execute('DELETE FROM raw;');
    }

    protected function execute(string|PDOStatement ...$commands): void
    {
        foreach ($commands as $command) {
            if (is_string($command)) {
                $command = (string) preg_replace(
                    [
                        '/^\s+/',
                        '/\s+/',
                        '/\(\s+/',
                        '/\s+\)/',
                        '/\s+;\s*$/',
                    ],
                    [
                        '',
                        ' ',
                        '(',
                        ')',
                        ';',
                    ],
                    $command
                );
                $this->pdo->exec($command);
            } elseif ($command instanceof PDOStatement) {
                $command->execute();
            }
        }
    }
}

<?php

declare(strict_types=1);

/**
 * This script is used to create the database using PDO and sqlite
 */

use Eclipxe\SepomexPhp\Importer\PdoImporter;

require_once __DIR__ . '/../vendor/autoload.php';

// escape the global scope
$returnValue = call_user_func(function () {
    try {
        $path = dirname(__DIR__) . '/assets';
        // create the path if this soes not exists
        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }
        // files
        $dbfile = $path . '/sepomex.db';
        $rawfile = $path . '/sepomex.txt';

        // raw file
        if (! file_exists($rawfile)) {
            $sourceurl = 'http://www.correosdemexico.gob.mx/datosabiertos/cp/cpdescarga.txt';
            echo "File $rawfile does not exists, will be downloaded from $sourceurl\n";
            copy($sourceurl, $rawfile);
        }

        // create the pdo object
        $pdo = new PDO('sqlite:' . $dbfile, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        // create the importer class
        $importer = new PdoImporter($pdo);

        // follow this steps
        $importer->createStruct();
        $importer->import($rawfile, null);
        return 0;
    } catch (Throwable $exception) {
        file_put_contents('php://stderr', $exception->getMessage(), FILE_APPEND);
        return 1;
    }
});

exit($returnValue);

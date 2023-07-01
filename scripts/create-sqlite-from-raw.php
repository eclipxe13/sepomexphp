<?php

/**
 * This script is used to create the database using PDO and sqlite
 */

declare(strict_types=1);

use Eclipxe\SepomexPhp\Downloader\SymfonyDownloader;
use Eclipxe\SepomexPhp\Importer\PdoImporter;

require_once __DIR__ . '/../vendor/autoload.php';

// escape the global scope
exit(call_user_func(function () {
    try {
        $path = dirname(__DIR__) . '/assets';
        // create the path if this does not exist
        if (! is_dir($path)) {
            mkdir($path, permissions: 0755, recursive: true);
        }
        // files
        $dbFile = $path . '/sepomex.db';
        $rawFile = $path . '/sepomex.txt';

        // raw file
        if (! file_exists($rawFile)) {
            $downloader = new SymfonyDownloader();
            printf("File %s does not exists, will be downloaded from %s\n", $rawFile, $downloader::LINK);
            $downloader->downloadTo($rawFile);
        }

        // create the pdo object
        $pdo = new PDO('sqlite:' . $dbFile, options: [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        // create the importer class
        $importer = new PdoImporter($pdo);

        // follow this steps
        $importer->createStruct();
        $importer->import($rawFile);
        return 0;
    } catch (Throwable $exception) {
        file_put_contents('php://stderr', $exception->getMessage() . PHP_EOL, FILE_APPEND);
        return 1;
    }
}));

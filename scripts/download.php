<?php

/**
 * This script is a command line tool to download the SEPOMEX text database
 * Usage: download.php assets/sepomex.txt
 */

declare(strict_types=1);

use Eclipxe\SepomexPhp\Downloader\Downloader;

require_once __DIR__ . '/../vendor/autoload.php';

exit(call_user_func(function (string $command, string ...$arguments): int {
    $defaultDestinationFile = dirname(__DIR__) . '/assets/sepomex.txt';
    if ([] !== array_intersect(['-h', '--help', 'help'], $arguments)) {
        $commandName = basename($command);
        printf(implode(PHP_EOL, [
            "$commandName script - scrap the sepomex database as text",
            'Syntax:',
            "    $commandName [destination-file] [--help|-h|help]",
            'Arguments:',
            "    destination-file: The path where the file will be downloaded, default: $defaultDestinationFile",
            '',
            '',
        ]));
        return 0;
    }

    $destinationFile = ($arguments[0] ?? '');
    if ('' === $destinationFile) {
        $destinationFile = $defaultDestinationFile;
    }
    $downloader = new Downloader();
    printf("Download from %s to %s\n", $downloader::LINK, $destinationFile);

    try {
        $downloader->downloadTo($destinationFile);
        return 0;
    } catch (Throwable $exception) {
        file_put_contents('php://stderr', $exception->getMessage() . PHP_EOL, FILE_APPEND);
        return 1;
    }
}, ...$argv));

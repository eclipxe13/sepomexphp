<?php

declare(strict_types=1);

use Eclipxe\SepomexPhp\Downloader\Downloader;

require_once __DIR__ . '/../vendor/autoload.php';

$path = dirname(__DIR__) . '/assets';
$destinationFile = $path . '/sepomex.txt';

$downloader = new Downloader();
printf("Download from %s to %s\n", $downloader::LINK, $destinationFile);
$downloader->downloadTo($destinationFile);

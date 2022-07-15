<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Downloader;

use RuntimeException;

interface DownloaderInterface
{
    /**
     * @param string $destinationFile
     * @throws RuntimeException
     * @return void
     */
    public function downloadTo(string $destinationFile): void;
}

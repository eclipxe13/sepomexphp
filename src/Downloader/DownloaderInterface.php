<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Downloader;

use RuntimeException;

interface DownloaderInterface
{
    /**
     * @throws RuntimeException
     */
    public function downloadTo(string $destinationFile): void;
}

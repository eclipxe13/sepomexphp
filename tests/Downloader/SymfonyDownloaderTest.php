<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Tests\Downloader;

use Eclipxe\SepomexPhp\Downloader\SymfonyDownloader;
use Eclipxe\SepomexPhp\Tests\TestCase;

final class SymfonyDownloaderTest extends TestCase
{
    public function testDownloaderCanDownload(): void
    {
        $downloader = new SymfonyDownloader();
        $destination = $this->fileTemp();
        unlink($destination);
        $downloader->downloadTo($destination);
        $this->assertFileExists($destination, 'Expected file was not found');
        $this->assertStringContainsString(
            'Catálogo Nacional de Códigos Postales',
            (string) iconv('iso8859-1', 'utf-8', $this->fileFirstLine($destination)),
            sprintf('Expected header on file %s was not found', $destination)
        );
    }
}

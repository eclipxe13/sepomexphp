<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Tests\Downloader;

use Eclipxe\SepomexPhp\Downloader\DownloaderInterface;
use Eclipxe\SepomexPhp\Downloader\GuzzleDownloader;
use Eclipxe\SepomexPhp\Downloader\PhpStreamsDownloader;
use Eclipxe\SepomexPhp\Downloader\SymfonyDownloader;
use Eclipxe\SepomexPhp\Tests\TestCase;
use RuntimeException;

final class DownloaderIntegrationTest extends TestCase
{
    private string $temporaryDestinationFile;

    protected function setUp(): void
    {
        parent::setUp();
        $temporaryDestinationFile = tempnam('', '');
        if (false === $temporaryDestinationFile) {
            throw new RuntimeException('Unable to create a temporary file');
        }
        unlink($temporaryDestinationFile);
        $this->temporaryDestinationFile = $temporaryDestinationFile;
    }

    protected function tearDown(): void
    {
        unlink($this->temporaryDestinationFile);
        parent::tearDown();
    }

    /** @return array<string, array{DownloaderInterface}> */
    public function providerDownloaderInstances(): array
    {
        return [
            'SymfonyDownloader' => [new SymfonyDownloader()],
            'GuzzleDownloader' => [new GuzzleDownloader()],
            'PhpStreams' => [new PhpStreamsDownloader()],
        ];
    }

    /** @dataProvider providerDownloaderInstances */
    public function testDownloaderCanDownload(DownloaderInterface $downloader): void
    {
        $destination = $this->temporaryDestinationFile;
        $downloader->downloadTo($destination);
        $this->assertFileExists($destination, 'Expected file was not found');
        $this->assertStringContainsString(
            'Catálogo Nacional de Códigos Postales',
            (string) iconv('iso8859-1', 'utf-8', $this->fileFirstLine($destination)),
            sprintf('Expected header on file %s was not found', $destination)
        );
    }

    private function fileFirstLine(string $file): string
    {
        $handler = fopen($file, 'r');
        if (false === $handler) {
            throw new RuntimeException(sprintf('Unable to open "%s"', $file));
        }
        return (string) fgets($handler);
    }
}

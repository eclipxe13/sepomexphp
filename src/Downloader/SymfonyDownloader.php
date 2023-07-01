<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Downloader;

use Eclipxe\SepomexPhp\Internal\ZipExtractor;
use RuntimeException;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\BrowserKit\Response;

final class SymfonyDownloader implements DownloaderInterface
{
    public function __construct(
        public readonly HttpBrowser $client = new HttpBrowser(),
    ) {
    }

    public function downloadTo(string $destinationFile): void
    {
        $crawler = $this->client->request('GET', self::LINK);
        $form = $crawler->selectButton('btnDescarga')->form();
        $this->client->submit($form, ['rblTipo' => 'txt']);
        /** @var Response $response */
        $response = $this->client->getResponse();

        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException(
                sprintf('Received a non 200 HTTP Status Code (code: %s)', $response->getStatusCode())
            );
        }

        if (false === $zipTempFile = tempnam('', '')) {
            throw new RuntimeException('Unable to create a temporary file');
        }
        if (false === file_put_contents($zipTempFile, $response->getContent())) {
            throw new RuntimeException("Unable to write data to $zipTempFile");
        }

        (new ZipExtractor())->extractFirstFileTo($zipTempFile, '*.txt', $destinationFile);
        unlink($zipTempFile);
    }
}

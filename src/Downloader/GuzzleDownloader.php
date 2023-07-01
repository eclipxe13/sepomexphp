<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Downloader;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use RuntimeException;

final class GuzzleDownloader implements DownloaderInterface
{
    use DownloaderTrait;

    public function __construct(
        public readonly ClientInterface $client = new Client(),
    ) {
    }

    public function downloadTo(string $destinationFile): void
    {
        if (false === $zipTempFile = tempnam('', '')) {
            throw new RuntimeException('Unable to create a temporary file');
        }

        $response = $this->client->request('POST', self::LINK, [
            RequestOptions::FORM_PARAMS => $this->fixedFormData(),
            RequestOptions::SINK => $zipTempFile,
        ]);
        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException(
                sprintf('Received a non 200 HTTP Status Code (code: %s)', $response->getStatusCode())
            );
        }
        if (! str_contains($response->getHeaderLine('Content-Disposition'), 'attachment')) {
            throw new RuntimeException(
                sprintf('Unexpected response content disposition: %s', $response->getHeaderLine('Content-Disposition'))
            );
        }

        $this->extractFirstFileTo($zipTempFile, '*.txt', $destinationFile);
        unlink($zipTempFile);
    }
}

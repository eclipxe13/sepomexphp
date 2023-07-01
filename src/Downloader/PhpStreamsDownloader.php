<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Downloader;

use RuntimeException;

final class PhpStreamsDownloader implements DownloaderInterface
{
    use DownloaderTrait;

    public function __construct()
    {
    }

    public function downloadTo(string $destinationFile): void
    {
        $resource = fopen(
            self::LINK,
            mode: 'r',
            context: stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => http_build_query($this->fixedFormData()),
                ],
            ])
        );
        if (false === $resource) {
            throw new RuntimeException('Unable to send POST data to download the resource');
        }
        $metadata = stream_get_meta_data($resource);
        $headers = $metadata['wrapper_data'];
        if (! is_array($headers)) {
            $headers = [];
        }
        if (! preg_match('#^HTTP/.*? 200 OK$#', $headers[0] ?? '')) {
            throw new RuntimeException(
                sprintf('Received a non valid HTTP response (%s)', $headers[0] ?? '')
            );
        }

        if (false === $zipTempFile = tempnam('', '')) {
            throw new RuntimeException('Unable to create a temporary file');
        }
        $zipTempStream = fopen($zipTempFile, 'w');
        if (false === $zipTempStream) {
            throw new RuntimeException('Unable to create a temporary stream');
        }
        if (! stream_copy_to_stream($resource, $zipTempStream)) {
            throw new RuntimeException('Unable to copy resource to a temporary file');
        }
        fclose($resource);
        fclose($zipTempStream);

        $this->extractFirstFileTo($zipTempFile, '*.txt', $destinationFile);
        unlink($zipTempFile);
    }
}

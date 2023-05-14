<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Downloader;

use RuntimeException;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\BrowserKit\Response;
use ZipArchive;

final class Downloader implements DownloaderInterface
{
    public const LINK = 'https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx';

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

        if (false === $zipTempFile = tempnam('', '')) {
            throw new RuntimeException('Unable to create a temporary file');
        }
        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException(
                sprintf('Received a non 200 HTTP Status Code (code: %s)', $response->getStatusCode())
            );
        }
        if (false === file_put_contents($zipTempFile, $response->getContent())) {
            throw new RuntimeException("Unable to write data to $zipTempFile");
        }

        $zipArchive = new ZipArchive();
        if (true !== $zipArchive->open($zipTempFile, ZipArchive::RDONLY)) {
            throw new RuntimeException('Cannot open downloaded data');
        }
        $selectedName = null;
        for ($i = 0; $i < $zipArchive->numFiles; $i++) {
            $currentName = (string) $zipArchive->getNameIndex($i);
            if (! preg_match('/^.*\.txt$/', $currentName)) {
                continue;
            }
            $selectedName = $currentName;
        }
        if (null === $selectedName) {
            throw new RuntimeException('Cannot find a text file inside the downloaded data');
        }
        if (false === $destinationStream = fopen($destinationFile, 'w')) {
            throw new RuntimeException("Unable to open or create $destinationFile");
        }
        if (false === $sourceStream = $zipArchive->getStream($selectedName)) {
            throw new RuntimeException("Unable to open stream from source $selectedName");
        }
        if (false === stream_copy_to_stream($sourceStream, $destinationStream)) {
            throw new RuntimeException("Unable to write contents on $destinationFile");
        }
        $zipArchive->close();
        unlink($zipTempFile);
    }
}

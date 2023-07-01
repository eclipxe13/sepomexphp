<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Downloader;

use RuntimeException;

interface DownloaderInterface
{
    public const LINK = 'https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx';

    /**
     * @throws RuntimeException
     */
    public function downloadTo(string $destinationFile): void;
}

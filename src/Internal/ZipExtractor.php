<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Internal;

use RuntimeException;
use ZipArchive;

/**
 * This class is a helper to extract from a ZIP file the first file that matches a pattern.
 *
 * @internal
 */
final class ZipExtractor
{
    public function extractFirstFileTo(string $zipPath, string $pattern, string $destinationPath): void
    {
        $zipArchive = new ZipArchive();
        if (true !== $zipArchive->open($zipPath, ZipArchive::RDONLY)) {
            throw new RuntimeException('Cannot open downloaded data');
        }
        $selectedName = null;
        for ($i = 0; $i < $zipArchive->numFiles; $i++) {
            $currentName = (string) $zipArchive->getNameIndex($i);
            if (! fnmatch($pattern, $currentName)) {
                continue;
            }
            $selectedName = $currentName;
        }
        if (null === $selectedName) {
            throw new RuntimeException(
                sprintf('Cannot find a text file that match "%s" inside the downloaded data', $pattern)
            );
        }
        if (false === $destinationStream = fopen($destinationPath, 'w')) {
            throw new RuntimeException("Unable to open or create $destinationPath");
        }
        if (false === $sourceStream = $zipArchive->getStream($selectedName)) {
            throw new RuntimeException("Unable to open stream from source $selectedName");
        }
        if (false === stream_copy_to_stream($sourceStream, $destinationStream)) {
            throw new RuntimeException("Unable to write contents on $destinationPath");
        }
        $zipArchive->close();
    }
}

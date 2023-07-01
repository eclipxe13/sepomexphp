<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Downloader;

use RuntimeException;
use ZipArchive;

/**
 * @internal
 */
trait DownloaderTrait
{
    /**
     * Returns a commonly known data that can be used to perform a POST request
     *
     * @return array<string, string>
     */
    private function fixedFormData(): array
    {
        return [
            '__EVENTTARGET' => '',
            '__EVENTARGUMENT' => '',
            '__LASTFOCUS' => '',
            '__VIEWSTATE' => '/wEPDwUINzcwOTQyOTgPZBYCAgEPZBYCAgEPZBYGAgMPDxYCHgRUZXh0BTjDmmx0aW1hIEFjdHVh'
                             . 'bGl6YWNpw7NuIGRlIEluZm9ybWFjacOzbjogSnVuaW8gMjkgZGUgMjAyM2RkAgcPEA8WBh4NRGF0'
                             . 'YVRleHRGaWVsZAUDRWRvHg5EYXRhVmFsdWVGaWVsZAUFSWRFZG8eC18hRGF0YUJvdW5kZ2QQFSEj'
                             . 'LS0tLS0tLS0tLSBUICBvICBkICBvICBzIC0tLS0tLS0tLS0OQWd1YXNjYWxpZW50ZXMPQmFqYSBD'
                             . 'YWxpZm9ybmlhE0JhamEgQ2FsaWZvcm5pYSBTdXIIQ2FtcGVjaGUUQ29haHVpbGEgZGUgWmFyYWdv'
                             . 'emEGQ29saW1hB0NoaWFwYXMJQ2hpaHVhaHVhEUNpdWRhZCBkZSBNw6l4aWNvB0R1cmFuZ28KR3Vh'
                             . 'bmFqdWF0bwhHdWVycmVybwdIaWRhbGdvB0phbGlzY28HTcOpeGljbxRNaWNob2Fjw6FuIGRlIE9j'
                             . 'YW1wbwdNb3JlbG9zB05heWFyaXQLTnVldm8gTGXDs24GT2F4YWNhBlB1ZWJsYQpRdWVyw6l0YXJv'
                             . 'DFF1aW50YW5hIFJvbxBTYW4gTHVpcyBQb3Rvc8OtB1NpbmFsb2EGU29ub3JhB1RhYmFzY28KVGFt'
                             . 'YXVsaXBhcwhUbGF4Y2FsYR9WZXJhY3J1eiBkZSBJZ25hY2lvIGRlIGxhIExsYXZlCFl1Y2F0w6Fu'
                             . 'CVphY2F0ZWNhcxUhAjAwAjAxAjAyAjAzAjA0AjA1AjA2AjA3AjA4AjA5AjEwAjExAjEyAjEzAjE0'
                             . 'AjE1AjE2AjE3AjE4AjE5AjIwAjIxAjIyAjIzAjI0AjI1AjI2AjI3AjI4AjI5AjMwAjMxAjMyFCsD'
                             . 'IWdnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2RkAh0PPCsACwBkGAEFHl9fQ29udHJv'
                             . 'bHNSZXF1aXJlUG9zdEJhY2tLZXlfXxYBBQtidG5EZXNjYXJnYXo4r2owexoHvaYUs8ZA4j6MDfNz',
            '__VIEWSTATEGENERATOR' => 'BE1A6D2E',
            '__EVENTVALIDATION' => '/wEWKAK0hrLiBALG/OLvBgLWk4iCCgLWk4SCCgLWk4CCCgLWk7yCCgLWk7iCCgLWk7SCCgLWk7CC'
                                   . 'CgLWk6yCCgLWk+iBCgLWk+SBCgLJk4iCCgLJk4SCCgLJk4CCCgLJk7yCCgLJk7iCCgLJk7SCCgLJ'
                                   . 'k7CCCgLJk6yCCgLJk+iBCgLJk+SBCgLIk4iCCgLIk4SCCgLIk4CCCgLIk7yCCgLIk7iCCgLIk7SC'
                                   . 'CgLIk7CCCgLIk6yCCgLIk+iBCgLIk+SBCgLLk4iCCgLLk4SCCgLLk4CCCgLL+uTWBALa4Za4AgK+'
                                   . 'qOyRAQLI56b6CwL1/KjtBZ0Iwsb2glbyqEbKgPFJYu0SWNmk',
            'cboEdo' => '00',
            'rblTipo' => 'txt',
            'btnDescarga.x' => '10',
            'btnDescarga.y' => '10',
        ];
    }

    private function extractFirstFileTo(string $zipPath, string $pattern, string $destinationPath): void
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

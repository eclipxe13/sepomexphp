<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data;

interface ExportableAsArray
{
    /**
     * Return the object as an array, exposing all its properties
     *
     * @phpstan-ignore-next-line Array definition must be set on each implementation
     */
    public function asArray(): array;
}

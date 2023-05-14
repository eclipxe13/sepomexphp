<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Importer;

final class RenamesRule
{
    public function __construct(
        public readonly string $from,
        public readonly string $to
    ) {
    }
}

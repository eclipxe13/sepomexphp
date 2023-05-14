<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data;

use JsonSerializable;

class LocationType implements JsonSerializable, ExportableAsArray
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }

    /** @return array{id: int, name: string} */
    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    /** @return array{id: int, name: string} */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data;

use JsonSerializable;

class Location implements JsonSerializable, ExportableAsArray
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly LocationType $type,
        public readonly District|null $district = null,
        public readonly City|null $city = null
    ) {
    }

    /**
     * @return array{
     *      id: int,
     *      name: string,
     *      type: array{id: int, name: string},
     *      district: null|array{id: int, name: string, state: array{id: int, name: string}},
     *      city: null|array{id: int, name: string}
     *  }
     */
    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->asArray(),
            'district' => $this->district?->asArray(),
            'city' => $this->city?->asArray(),
        ];
    }

    /**
     * @return array{
     *      id: int,
     *      name: string,
     *      type: LocationType,
     *      district: District|null,
     *      city: City|null
     *  }
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'district' => $this->district,
            'city' => $this->city,
        ];
    }
}

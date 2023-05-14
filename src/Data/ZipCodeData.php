<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data;

use Eclipxe\SepomexPhp\Internal\DataValidation;
use JsonSerializable;

class ZipCodeData implements JsonSerializable, ExportableAsArray
{
    public readonly string $formatted;

    public readonly State $state;

    public readonly Cities $cities;

    public function __construct(
        public readonly string $zipcode,
        public readonly Locations $locations,
        public readonly District $district,
    ) {
        DataValidation::validateZipCode($this->zipcode);
        $this->formatted = str_pad($this->zipcode, 5, '0', STR_PAD_LEFT);
        $this->state = $this->district->state;
        $this->cities = $this->locations->cities;
    }

    /**
     * @return array{
     *     zipcode: string,
     *     locations: array<array{
     *         id: int,
     *         name: string,
     *         type: array{id: int, name: string},
     *         district: null|array{id: int, name: string, state: array{id: int, name: string}},
     *         city: null|array{id: int, name: string}
     *         }>,
     *     cities: array<array{id: int, name: string}>,
     *     district: array{id: int, name: string, state: array{id: int, name: string}},
     *     state: array{id: int, name: string}
     * }
     */
    public function asArray(): array
    {
        return [
            'zipcode' => $this->zipcode,
            'locations' => $this->locations->asArray(),
            'cities' => $this->cities->asArray(),
            'district' => $this->district->asArray(),
            'state' => $this->state->asArray(),
        ];
    }

    /**
     * @return array{
     *     zipcode: string,
     *     locations: Locations,
     *     cities: Cities,
     *     district: District,
     *     state: State
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'zipcode' => $this->zipcode,
            'locations' => $this->locations,
            'cities' => $this->cities,
            'district' => $this->district,
            'state' => $this->state,
        ];
    }
}

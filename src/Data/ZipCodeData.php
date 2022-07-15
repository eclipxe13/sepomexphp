<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data;

use Eclipxe\SepomexPhp\Data\Traits\PropertyDistrictTrait;
use Eclipxe\SepomexPhp\Data\Traits\PropertyLocationsTrait;
use InvalidArgumentException;

class ZipCodeData
{
    use PropertyLocationsTrait;
    use PropertyDistrictTrait;

    private string $zipcode;

    private string $formatted;

    /**
     * @param string $zipcode
     * @param Locations $locations
     * @param District $district
     * @throws InvalidArgumentException when zipcode is not 4 or 5 digits
     */
    public function __construct(string $zipcode, Locations $locations, District $district)
    {
        if (! preg_match('/^\d{4,5}$/', $zipcode)) {
            throw new InvalidArgumentException('Zipcode must be 4 to 5 digits');
        }
        $this->zipcode = $zipcode;
        $this->formatted = str_pad($this->zipcode, 5, '0', STR_PAD_LEFT);
        $this->setLocations($locations);
        $this->setDistrict($district);
    }

    public function zipcode(): string
    {
        return $this->zipcode;
    }

    public function format(): string
    {
        return $this->formatted;
    }

    public function state(): State
    {
        return $this->district()->state();
    }

    /**
     * @return array{
     *     zipcode: string,
     *     locations: array<array{
     *         id: int,
     *         name: string,
     *         fullname: string,
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
        $locations = $this->locations();
        return [
            'zipcode' => $this->zipcode(),
            'locations' => $locations->asArray(),
            'cities' => $locations->cities()->asArray(),
            'district' => $this->district()->asArray(),
            'state' => $this->state()->asArray(),
        ];
    }
}

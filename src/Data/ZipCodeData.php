<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data;

use Eclipxe\SepomexPhp\Data\Traits\PropertyDistrictTrait;
use Eclipxe\SepomexPhp\Data\Traits\PropertyLocationsTrait;
use Eclipxe\SepomexPhp\Data\Traits\PropertyStateTrait;
use UnexpectedValueException;

class ZipCodeData
{
    use PropertyLocationsTrait;
    use PropertyDistrictTrait;
    use PropertyStateTrait;

    private string $zipcode;

    private string $formatted;

    /**
     * @param string $zipcode
     * @param Locations $locations
     * @param District $district
     * @param State $state
     * @throws UnexpectedValueException when zipcode is not 4 or 5 digits
     */
    public function __construct(string $zipcode, Locations $locations, District $district, State $state)
    {
        if (1 !== preg_match('/^\d{4,5}$/', $zipcode)) {
            throw new UnexpectedValueException('Zipcode must be 4 to 5 digits');
        }
        $this->zipcode = $zipcode;
        $this->formatted = str_pad($this->zipcode, 5, '0', STR_PAD_LEFT);
        $this->setLocations($locations);
        $this->setDistrict($district);
        $this->setState($state);
    }

    public function zipcode(): string
    {
        return $this->zipcode;
    }

    public function format(): string
    {
        return $this->formatted;
    }

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
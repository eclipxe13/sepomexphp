<?php

declare(strict_types=1);

namespace SepomexPhp;

use SepomexPhp\Traits\PropertyDistrictTrait;
use SepomexPhp\Traits\PropertyLocationsTrait;
use SepomexPhp\Traits\PropertyStateTrait;
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
     * @param Location[] $locations
     * @param District $district
     * @param State $state
     */
    public function __construct(string $zipcode, array $locations, District $district, State $state)
    {
        if (1 !== preg_match('/^\d{4,5}$/', $zipcode)) {
            throw new UnexpectedValueException('Zipcode must be 4 to 5 digits');
        }
        $this->zipcode = $zipcode;
        $this->formatted = str_pad($this->zipcode, 5, '0', STR_PAD_LEFT);
        $this->setLocations(...$locations);
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

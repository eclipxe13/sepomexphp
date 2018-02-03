<?php
namespace SepomexPhp;

use SepomexPhp\Traits\PropertyDistrictTrait;
use SepomexPhp\Traits\PropertyLocationsTrait;
use SepomexPhp\Traits\PropertyStateTrait;

class ZipCodeData
{
    use PropertyLocationsTrait;
    use PropertyDistrictTrait;
    use PropertyStateTrait;

    /** @var int */
    private $zipcode;

    /**
     * @param int $zipcode
     * @param Location[] $locations
     * @param District $district
     * @param State $state
     */
    public function __construct(int $zipcode, array $locations, District $district, State $state)
    {
        $this->zipcode = $zipcode;
        $this->setLocations(...$locations);
        $this->setDistrict($district);
        $this->setState($state);
    }

    public function zipcode(): int
    {
        return $this->zipcode;
    }

    public function format(): string
    {
        return str_pad((string) $this->zipcode, 5, '0', STR_PAD_LEFT);
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

<?php
namespace SepomexPhp;

use SepomexPhp\Traits\PropertyCityTrait;
use SepomexPhp\Traits\PropertyDistrictTrait;
use SepomexPhp\Traits\PropertyIdIntegerTrait;
use SepomexPhp\Traits\PropertyNameStringTrait;

class Location
{
    use PropertyIdIntegerTrait;
    use PropertyNameStringTrait;
    use PropertyDistrictTrait;
    use PropertyCityTrait;

    /** @var LocationType */
    private $type;

    public function __construct(int $id, string $name, LocationType $type, District $district = null, City $city = null)
    {
        $this->setId($id);
        $this->setName($name);
        $this->type = $type;
        $this->setDistrict($district);
        $this->setCity($city);
    }

    public function type(): LocationType
    {
        return $this->type;
    }

    public function getFullName(): string
    {
        return $this->name() . ' (' . $this->type()->name() . ')';
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'fullname' => $this->getFullName(),
            'type' => $this->type()->asArray(),
            'district' => $this->hasDistrict() ? $this->district()->asArray() : null,
            'city' => $this->hasCity() ? $this->city()->asArray() : null,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp;

use Eclipxe\SepomexPhp\Traits\PropertyCityTrait;
use Eclipxe\SepomexPhp\Traits\PropertyDistrictTrait;
use Eclipxe\SepomexPhp\Traits\PropertyIdIntegerTrait;
use Eclipxe\SepomexPhp\Traits\PropertyNameStringTrait;

class Location
{
    use PropertyIdIntegerTrait;
    use PropertyNameStringTrait;
    use PropertyDistrictTrait;
    use PropertyCityTrait;

    private LocationType $type;

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

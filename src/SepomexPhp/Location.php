<?php
namespace SepomexPhp;

class Location
{
    use PropertyIdIntegerTrait;
    use PropertyNameStringTrait;
    use PropertyDistrictTrait;
    use PropertyCityTrait;

    /** @var string */
    private $type;

    public function __construct(int $id, string $name, string $type, District $district = null, City $city = null)
    {
        $this->setId($id);
        $this->setName($name);
        $this->type = $type;
        $this->setDistrict($district);
        $this->setCity($city);
    }

    public function type(): string
    {
        return $this->type;
    }

    public function getFullName(): string
    {
        return $this->name . ' (' . $this->type . ')';
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'district' => $this->hasDistrict() ? $this->district()->asArray() : null,
            'city' => $this->hasCity() ? $this->city()->asArray() : null,
        ];
    }
}

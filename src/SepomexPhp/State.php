<?php
namespace SepomexPhp;

class State
{
    use PropertyIdIntegerTrait;
    use PropertyNameStringTrait;
    use PropertyLocationsTrait;

    /**
     * @param int $id
     * @param string $name
     * @param Location[] $locations
     */
    public function __construct(int $id, string $name, array $locations = [])
    {
        $this->setId($id);
        $this->setName($name);
        $this->setLocations(...$locations);
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'locations' => $this->locations()->asArray(),
        ];
    }
}

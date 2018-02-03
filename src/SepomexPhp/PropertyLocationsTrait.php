<?php
namespace SepomexPhp;

trait PropertyLocationsTrait
{
    /** @var Locations */
    private $locations;

    /**
     * @return Locations|Location[]
     */
    public function locations(): Locations
    {
        return $this->locations;
    }

    protected function setLocations(Location ...$locations)
    {
        $this->locations = new Locations(...$locations);
    }
}

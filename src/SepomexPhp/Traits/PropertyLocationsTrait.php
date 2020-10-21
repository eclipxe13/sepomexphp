<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Traits;

use Eclipxe\SepomexPhp\Location;
use Eclipxe\SepomexPhp\Locations;

trait PropertyLocationsTrait
{
    private Locations $locations;

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

<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data\Traits;

use Eclipxe\SepomexPhp\Data\Location;
use Eclipxe\SepomexPhp\Data\Locations;

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

    protected function setLocations(Locations $locations)
    {
        $this->locations = $locations;
    }
}

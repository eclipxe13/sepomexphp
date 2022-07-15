<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data\Traits;

use Eclipxe\SepomexPhp\Data\Locations;

trait PropertyLocationsTrait
{
    private Locations $locations;

    public function locations(): Locations
    {
        return $this->locations;
    }

    protected function setLocations(Locations $locations): void
    {
        $this->locations = $locations;
    }
}

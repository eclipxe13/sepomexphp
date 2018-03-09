<?php
declare(strict_types=1);
namespace SepomexPhp\Traits;

use SepomexPhp\Location;
use SepomexPhp\Locations;

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

<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp;

use Eclipxe\SepomexPhp\Data\City;
use Eclipxe\SepomexPhp\Data\District;
use Eclipxe\SepomexPhp\Data\Location;
use Eclipxe\SepomexPhp\Data\Locations;
use Eclipxe\SepomexPhp\Data\LocationType;
use Eclipxe\SepomexPhp\Data\State;
use Eclipxe\SepomexPhp\Data\ZipCodeData;

/**
 * Factory, do not include new statements on Eclipxe\SepomexPhp class
 *
 * @access private
 */
class Factory
{
    public function newZipCodeData(string $zipcode, Locations $locations, District $district): ZipCodeData
    {
        return new ZipCodeData($zipcode, $locations, $district);
    }

    public function newState(int $id, string $name): State
    {
        return new State($id, $name);
    }

    public function newDistrict(int $id, string $name, State $state): District
    {
        return new District($id, $name, $state);
    }

    public function newCity(int $id, string $name): City
    {
        return new City($id, $name);
    }

    public function newLocation(
        int $id,
        string $name,
        LocationType $type,
        District $district = null,
        City $city = null
    ): Location {
        return new Location($id, $name, $type, $district, $city);
    }

    public function newLocationType(int $id, string $name): LocationType
    {
        return new LocationType($id, $name);
    }
}

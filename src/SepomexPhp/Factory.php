<?php

namespace SepomexPhp;

/**
 * Factory, do not include new statements on SepomexPhp class
 * @access private
 * @package SepomexPhp
 */
class Factory
{

    public function newZipCodeData($zipcode, array $locations, District $district, State $state)
    {
        return new ZipCodeData($zipcode, $locations, $district, $state);
    }

    public function newState($id, $name)
    {
        return new State($id, $name);
    }

    public function newDistrict($id, $name, State $state = null)
    {
        return new District($id, $name, $state);
    }

    public function newCity($id, $name, State $state = null)
    {
        return new City($id, $name, $state);
    }

    public function newLocation($id, $name, $type, District $district = null, City $city = null)
    {
        return new Location($id, $name, $type, $district, $city);
    }


}
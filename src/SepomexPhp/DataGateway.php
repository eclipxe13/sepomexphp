<?php
/**
 * Created by PhpStorm.
 * User: eclipxe
 * Date: 9/08/15
 * Time: 10:44 PM
 */

namespace SepomexPhp;

interface DataGateway
{
    /**
     * Get zipcode data
     * This return null if no zipcode where found
     * @param int $zipcode
     * @return array|null Assoc array with keys: zipcode, iddistrict, districtname, idstate, statename
     */
    public function getZipCodeData($zipcode);

    /**
     * Get a Trasversable list of locations data
     * @param int $zipcode
     * @return array An indexed array containing assoc array with keys: id, name, type, idcity, cityname
     */
    public function getLocationsFromZipCode($zipcode);
}

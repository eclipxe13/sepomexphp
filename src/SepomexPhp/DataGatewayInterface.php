<?php
namespace SepomexPhp;

interface DataGatewayInterface
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
     * @return array An indexed array containing assoc array with keys: id, name, idtype, typename, idcity, cityname
     */
    public function getLocationsFromZipCode($zipcode);
}

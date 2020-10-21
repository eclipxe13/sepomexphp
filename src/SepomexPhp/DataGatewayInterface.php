<?php

declare(strict_types=1);

namespace SepomexPhp;

interface DataGatewayInterface
{
    /**
     * Get zipcode data
     *
     * @param string $zipcode
     * @return array Assoc array with keys: zipcode, iddistrict, districtname, idstate, statename
     */
    public function getZipCodeData(string $zipcode): array;

    /**
     * Get a list of locations data
     * @param string $zipcode
     * @return array An indexed array containing assoc array with keys: id, name, idtype, typename, idcity, cityname
     */
    public function getLocationsFromZipCode(string $zipcode): array;

    /**
     * Get a list of districts that match with district name and state name
     *
     * @param string $districtName
     * @param string $stateName
     * @param int $pageIndex
     * @param int $pageSize
     * @return array
     */
    public function searchDistricts(string $districtName, string $stateName, int $pageIndex, int $pageSize): array;
}

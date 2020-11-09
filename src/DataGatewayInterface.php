<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp;

interface DataGatewayInterface
{
    /**
     * Get zipcode data
     *
     * @param string $zipcode
     * @return array Assoc array with keys: zipcode, iddistrict, districtname, idstate, statename
     * @throws DataGatewayQueryException
     */
    public function getZipCodeData(string $zipcode): array;

    /**
     * Get a list of locations data
     * @param string $zipcode
     * @return array An indexed array containing assoc array with keys: id, name, idtype, typename, idcity, cityname
     * @throws DataGatewayQueryException
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
     * @throws DataGatewayQueryException
     */
    public function searchDistricts(string $districtName, string $stateName, int $pageIndex, int $pageSize): array;
}

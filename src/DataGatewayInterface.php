<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp;

interface DataGatewayInterface
{
    /**
     * Get zipcode data
     * Returns an assoc array with keys: id, name, idtype, typename, idcity, cityname
     *
     * @param string $zipcode
     * @return array<string, scalar|null>
     * @throws DataGatewayQueryException
     */
    public function getZipCodeData(string $zipcode): array;

    /**
     * Get a list of locations data
     * Returns an indexed array containing assoc array with keys: id, name, idtype, typename, idcity, cityname
     *
     * @param string $zipcode
     * @return array<int, array<string, scalar|null>>
     * @throws DataGatewayQueryException
     */
    public function getLocationsFromZipCode(string $zipcode): array;

    /**
     * Get a list of districts that match with district name and state name
     * Comparison is optional and match if the field contains on the search
     * Returns an indexed array containing assoc array with keys: id, name, idstate, statename
     *
     * @param string $districtName
     * @param string $stateName
     * @param int $pageIndex
     * @param int $pageSize
     * @return array<array<string, scalar|null>>
     * @throws DataGatewayQueryException
     */
    public function searchDistricts(string $districtName, string $stateName, int $pageIndex, int $pageSize): array;
}

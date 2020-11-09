<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp;

use Eclipxe\SepomexPhp\Data\District;
use Eclipxe\SepomexPhp\Data\Locations;
use Eclipxe\SepomexPhp\Data\ZipCodeData;

class SepomexPhp
{
    protected DataGatewayInterface $gateway;

    protected Factory $factory;

    /**
     * @param DataGatewayInterface $gateway
     * @param Factory|null $factory Change the object creation factory, if null the internal factory will be instanced
     */
    public function __construct(DataGatewayInterface $gateway, Factory $factory = null)
    {
        $this->gateway = $gateway;
        $this->factory = $factory ?? new Factory();
    }

    /**
     * Return a ZipCodeData object or null if not found
     * @param string $zipcode
     * @return ZipCodeData
     */
    public function getZipCodeData(string $zipcode): ?ZipCodeData
    {
        // get data information
        $data = $this->gateway->getZipCodeData($zipcode);
        if ([] === $data) {
            return null;
        }

        $state = $this->factory->newState((int) $data['idstate'], $data['statename']);
        $district = $this->factory->newDistrict((int) $data['iddistrict'], $data['districtname'], $state);
        $locations = $this->getLocationsFromZipCode($zipcode);
        return $this->factory->newZipCodeData($zipcode, $locations, $district);
    }

    /**
     * @param string $zipcode
     * @return Locations
     */
    public function getLocationsFromZipCode(string $zipcode): Locations
    {
        $locations = [];
        $items = $this->gateway->getLocationsFromZipCode($zipcode);
        foreach ($items as $item) {
            $locations[] = $this->factory->newLocation(
                (int) $item['id'],
                $item['name'],
                $this->factory->newLocationType((int) $item['idtype'], $item['typename']),
                null,
                ($item['idcity']) ? $this->factory->newCity((int) $item['idcity'], $item['cityname']) : null
            );
        }
        return new Locations(...$locations);
    }

    /**
     * @param string $districtName
     * @param string $stateName
     * @param int $pageIndex
     * @param int $pageSize
     * @return District[]
     */
    public function searchDistricts(
        string $districtName,
        string $stateName,
        int $pageIndex = 0,
        int $pageSize = 100
    ): array {
        $states = [];
        $districts = [];
        $rows = $this->gateway->searchDistricts($districtName, $stateName, $pageIndex, $pageSize);
        foreach ($rows as $row) {
            if (! array_key_exists($row['idstate'], $states)) {
                $states[$row['idstate']] = $this->factory->newState((int) $row['idstate'], $row['statename']);
            }
            $districts[] = $this->factory->newDistrict((int) $row['id'], $row['name'], $states[$row['idstate']]);
        }
        return $districts;
    }
}

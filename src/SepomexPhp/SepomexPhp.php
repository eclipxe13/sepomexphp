<?php

declare(strict_types=1);

namespace SepomexPhp;

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
     * @return ZipCodeData|null
     */
    public function getZipCodeData(string $zipcode)
    {
        // get data information
        $data = $this->gateway->getZipCodeData($zipcode);
        // no data, return null
        if (null === $data) {
            return null;
        }
        // return ZipCodeData array
        return $this->factory->newZipCodeData(
            $zipcode,
            $this->getLocationsFromZipCode($zipcode),
            $this->factory->newDistrict((int) $data['iddistrict'], $data['districtname']),
            $this->factory->newState((int) $data['idstate'], $data['statename'])
        );
    }

    /**
     * @param string $zipcode
     * @return Location[]
     */
    public function getLocationsFromZipCode(string $zipcode): array
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
        return $locations;
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

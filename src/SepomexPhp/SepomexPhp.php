<?php

namespace SepomexPhp;

class SepomexPhp
{
    /**
     * @var DataGateway
     */
    protected $gateway;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @param DataGateway $gateway
     * @param Factory|null $factory Change the object creation factory, if null the internal factory will be instanced
     */
    public function __construct(DataGateway $gateway, Factory $factory = null)
    {
        $this->gateway = $gateway;
        if (null === $factory) {
            $factory = new Factory();
        }
        $this->factory = $factory;
    }

    /**
     * Return a ZipCodeData object or null if not found
     * @param int $zipcode
     * @return ZipCodeData|null
     */
    public function getZipCodeData($zipcode)
    {
        // fix input type
        if (is_string($zipcode)) {
            $zipcode = intval($zipcode);
        }
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
            $this->factory->newDistrict($data['iddistrict'], $data['districtname']),
            $this->factory->newState($data['idstate'], $data['statename'])
        );
    }

    /**
     * @param int $zipcode
     * @return Location[]
     */
    public function getLocationsFromZipCode($zipcode)
    {
        $locations = [];
        $items = $this->gateway->getLocationsFromZipCode($zipcode);
        foreach ($items as $item) {
            $locations[] = $this->factory->newLocation(
                $item['id'],
                $item['name'],
                $item['type'],
                null,
                ($item['idcity']) ? $this->factory->newCity($item['idcity'], $item['cityname']) : null
            );
        }
        return $locations;
    }
}

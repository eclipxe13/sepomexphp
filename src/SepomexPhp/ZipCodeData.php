<?php
namespace SepomexPhp;

class ZipCodeData
{
    /**
     * @var int
     */
    public $zipcode;
    /**
     * @var Location[]
     */
    public $locations;
    /**
     * @var District
     */
    public $district;
    /**
     * @var State
     */
    public $state;

    /**
     * @param int $zipcode
     * @param Location[] $locations
     * @param District $district
     * @param State $state
     */
    public function __construct($zipcode, array $locations, District $district, State $state)
    {
        foreach ($locations as $location) {
            if (! ($location instanceof Location)) {
                throw new \InvalidArgumentException('locations must be an array of ' . Location::class);
            }
        }
        $this->zipcode = $zipcode;
        $this->locations = $locations;
        $this->district = $district;
        $this->state = $state;
    }
}

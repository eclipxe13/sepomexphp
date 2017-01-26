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
     * @param array $locations
     * @param District $district
     * @param State $state
     */
    public function __construct($zipcode, array $locations, District $district, State $state)
    {
        $this->zipcode = $zipcode;
        $this->locations = $locations;
        $this->district = $district;
        $this->state = $state;
    }
}

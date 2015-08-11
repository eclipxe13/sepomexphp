<?php
/**
 * Created by PhpStorm.
 * User: eclipxe
 * Date: 10/08/15
 * Time: 12:16 AM
 */

namespace SepomexPhp;

class Location
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $type;

    /** @var District */
    public $district;

    /** @var City */
    public $city;

    /**
     * @param int $id
     * @param string $name
     * @param string $type
     * @param District|null $district
     * @param City|null $city
     */
    public function __construct($id, $name, $type, District $district = null, City $city = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->district = $district;
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->name . ' (' . $this->type . ')';
    }

}
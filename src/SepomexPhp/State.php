<?php
/**
 * Created by PhpStorm.
 * User: eclipxe
 * Date: 9/08/15
 * Time: 11:39 PM
 */

namespace SepomexPhp;

class State
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $locations;

    /**
     * @param int $id
     * @param string $name
     * @param array $locations
     */
    public function __construct($id, $name, array $locations = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->locations = $locations;
    }
}
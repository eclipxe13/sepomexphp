<?php
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
     * @var Location[]
     */
    public $locations;

    /**
     * @param int $id
     * @param string $name
     * @param Location[] $locations
     */
    public function __construct($id, $name, array $locations = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->locations = $locations;
    }
}

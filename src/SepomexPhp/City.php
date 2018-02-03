<?php
namespace SepomexPhp;

class City
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var State|null */
    public $state;

    /**
     * City constructor.
     * @param int $id
     * @param string $name
     * @param State|null $state
     */
    public function __construct($id, $name, State $state = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->state = $state;
    }
}

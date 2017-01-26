<?php
namespace SepomexPhp;

class District
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var State */
    public $state;

    /**
     * District constructor.
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

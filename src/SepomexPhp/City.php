<?php
/**
 * Created by PhpStorm.
 * User: eclipxe
 * Date: 9/08/15
 * Time: 11:49 PM
 */

namespace SepomexPhp;

class City
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var State */
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

<?php
namespace SepomexPhp;

trait PropertyIdIntegerTrait
{
    /** @var int */
    private $id;

    public function id(): int
    {
        return $this->id;
    }

    protected function setId(int $id)
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('Value cannot be less than or equal to zero');
        }
        $this->id = $id;
    }
}

<?php

declare(strict_types=1);

namespace SepomexPhp\Traits;

trait PropertyIdIntegerTrait
{
    private int $id;

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

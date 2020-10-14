<?php

declare(strict_types=1);

namespace SepomexPhp\Traits;

use InvalidArgumentException;

trait PropertyNameStringTrait
{
    private string $name;

    public function name(): string
    {
        return $this->name;
    }

    protected function setName(string $name)
    {
        if ('' === $name) {
            throw new InvalidArgumentException('Name property cannot be empty');
        }
        $this->name = $name;
    }
}

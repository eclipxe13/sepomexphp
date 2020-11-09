<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data;

use Eclipxe\SepomexPhp\Data\Traits\PropertyIdIntegerTrait;
use Eclipxe\SepomexPhp\Data\Traits\PropertyNameStringTrait;
use Eclipxe\SepomexPhp\Data\Traits\PropertyStateTrait;

class City
{
    use PropertyIdIntegerTrait;
    use PropertyNameStringTrait;
    use PropertyStateTrait;

    public function __construct(int $id, string $name, State $state = null)
    {
        $this->setId($id);
        $this->setName($name);
        $this->setState($state);
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'state' => $this->hasState() ? $this->state()->asArray() : null,
        ];
    }
}

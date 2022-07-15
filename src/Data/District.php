<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data;

use Eclipxe\SepomexPhp\Data\Traits\PropertyIdIntegerTrait;
use Eclipxe\SepomexPhp\Data\Traits\PropertyNameStringTrait;
use Eclipxe\SepomexPhp\Data\Traits\PropertyStateTrait;

class District
{
    use PropertyIdIntegerTrait;
    use PropertyNameStringTrait;
    use PropertyStateTrait;

    public function __construct(int $id, string $name, State $state)
    {
        $this->setId($id);
        $this->setName($name);
        $this->setState($state);
    }

    /** @return array{id: int, name: string, state: array{id: int, name: string}} */
    public function asArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'state' => $this->state()->asArray(),
        ];
    }
}

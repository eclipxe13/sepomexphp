<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp;

use Eclipxe\SepomexPhp\Traits\PropertyIdIntegerTrait;
use Eclipxe\SepomexPhp\Traits\PropertyNameStringTrait;
use Eclipxe\SepomexPhp\Traits\PropertyStateTrait;

class District
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

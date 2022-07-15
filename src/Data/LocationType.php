<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data;

use Eclipxe\SepomexPhp\Data\Traits\PropertyIdIntegerTrait;
use Eclipxe\SepomexPhp\Data\Traits\PropertyNameStringTrait;

class LocationType
{
    use PropertyIdIntegerTrait;
    use PropertyNameStringTrait;

    public function __construct(int $id, string $name)
    {
        $this->setId($id);
        $this->setName($name);
    }

    /** @return array{id: int, name: string} */
    public function asArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
        ];
    }
}

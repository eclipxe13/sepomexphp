<?php

declare(strict_types=1);

namespace SepomexPhp;

use SepomexPhp\Traits\PropertyIdIntegerTrait;
use SepomexPhp\Traits\PropertyNameStringTrait;

class LocationType
{
    use PropertyIdIntegerTrait;
    use PropertyNameStringTrait;

    public function __construct(int $id, string $name)
    {
        $this->setId($id);
        $this->setName($name);
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
        ];
    }
}

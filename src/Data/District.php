<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data;

use Eclipxe\SepomexPhp\Internal\DataValidation;
use JsonSerializable;

class District implements JsonSerializable, ExportableAsArray
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly State $state,
    ) {
        DataValidation::validateArgumentId($this->id);
        DataValidation::validateName($this->name);
    }

    /** @return array{id: int, name: string, state: array{id: int, name: string}} */
    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'state' => $this->state->asArray(),
        ];
    }

    /** @return array{id: int, name: string, state: State} */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'state' => $this->state,
        ];
    }
}

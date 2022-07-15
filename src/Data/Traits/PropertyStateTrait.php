<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data\Traits;

use Eclipxe\SepomexPhp\Data\State;

trait PropertyStateTrait
{
    private State $state;

    public function state(): State
    {
        return $this->state;
    }

    protected function setState(State $state): void
    {
        $this->state = $state;
    }
}

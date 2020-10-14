<?php

declare(strict_types=1);

namespace SepomexPhp\Traits;

use LogicException;
use SepomexPhp\State;

trait PropertyStateTrait
{
    private ?State $state = null;

    public function state(): State
    {
        if (! $this->state instanceof State) {
            throw new LogicException('Try to access the state when no exists');
        }
        return $this->state;
    }

    public function hasState(): bool
    {
        return ($this->state instanceof State);
    }

    protected function setState(State $state = null)
    {
        $this->state = $state;
    }
}

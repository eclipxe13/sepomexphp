<?php
namespace SepomexPhp;

trait PropertyStateTrait
{
    /** @var State|null */
    private $state;

    public function state(): State
    {
        if (! $this->state instanceof State) {
            throw new \LogicException('Try to access the state when no exists');
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

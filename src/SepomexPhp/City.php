<?php
namespace SepomexPhp;

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

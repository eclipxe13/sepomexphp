<?php
namespace SepomexPhp;

trait PropertyNameStringTrait
{
    /** @var string */
    private $name;

    public function name(): string
    {
        return $this->name;
    }

    protected function setName(string $name)
    {
        if ('' === $name) {
            throw new \InvalidArgumentException('Name property cannot be empty');
        }
        $this->name = $name;
    }
}

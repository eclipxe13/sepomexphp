<?php

declare(strict_types=1);

namespace SepomexPhp;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OutOfRangeException;

class Cities implements IteratorAggregate, Countable
{
    /** @var City[] */
    private array $collection;

    public function __construct(City ...$city)
    {
        $this->collection = $city;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->collection);
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function byIndex(int $index)
    {
        if (array_key_exists($index, $this->collection)) {
            return $this->collection[$index];
        }
        throw new OutOfRangeException('Index out of bounds');
    }

    public function asArray(): array
    {
        $array = [];
        foreach ($this->collection as $city) {
            $array[] = $city->asArray();
        }
        return $array;
    }
}

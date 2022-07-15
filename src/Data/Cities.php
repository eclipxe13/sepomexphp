<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OutOfRangeException;
use Traversable;

/**
 * City Collection
 *
 * @implements IteratorAggregate<City>
 */
class Cities implements IteratorAggregate, Countable
{
    /** @var City[] */
    private array $collection;

    public function __construct(City ...$city)
    {
        $this->collection = $city;
    }

    /** @return Traversable<City> */
    public function getIterator()
    {
        return new ArrayIterator($this->collection);
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function byIndex(int $index): City
    {
        if (! isset($this->collection[$index])) {
            throw new OutOfRangeException('Index out of bounds');
        }
        return $this->collection[$index];
    }

    /** @return array<array{id: int, name: string}> */
    public function asArray(): array
    {
        return array_map(
            fn (City $city): array => $city->asArray(),
            $this->collection
        );
    }
}

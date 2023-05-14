<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use OutOfRangeException;
use Traversable;

/**
 * Location Collection
 *
 * @implements IteratorAggregate<Location>
 */
class Locations implements IteratorAggregate, Countable, JsonSerializable, ExportableAsArray
{
    /** @var Location[] */
    private readonly array $collection;

    public readonly Cities $cities;

    public function __construct(Location ...$location)
    {
        $this->collection = array_values($location);
        $this->cities = $this->extractUniqueCities();
    }

    private function extractUniqueCities(): Cities
    {
        $cities = [];
        foreach ($this->collection as $location) {
            if (null !== $location->city) {
                $city = $location->city;
                $cities[$city->id] = $city;
            }
        }
        return new Cities(...$cities);
    }

    public function first(): Location
    {
        return $this->byIndex(0);
    }

    public function byIndex(int $index): Location
    {
        if (! isset($this->collection[$index])) {
            throw new OutOfRangeException('Index out of bounds');
        }
        return $this->collection[$index];
    }

    /** @return Traversable<Location> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->collection);
    }

    public function count(): int
    {
        return count($this->collection);
    }

    /**
     * @return array<array{
     *     id: int,
     *     name: string,
     *     type: array{id: int, name: string},
     *     district: null|array{id: int, name: string, state: array{id: int, name: string}},
     *     city: null|array{id: int, name: string}
     *     }>
     */
    public function asArray(): array
    {
        return array_map(
            fn (Location $location): array => $location->asArray(),
            $this->collection
        );
    }

    /** @return Location[] */
    public function jsonSerialize(): array
    {
        return $this->collection;
    }
}

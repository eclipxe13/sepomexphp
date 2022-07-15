<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Data;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * Location Collection
 *
 * @implements IteratorAggregate<Location>
 */
class Locations implements IteratorAggregate, Countable
{
    /** @var Location[] */
    private array $collection;

    private Cities $cities;

    public function __construct(Location ...$location)
    {
        $this->collection = $location;
        $this->cities = $this->extractUniqueCities();
    }

    public function extractUniqueCities(): Cities
    {
        $cities = [];
        foreach ($this->collection as $location) {
            if ($location->hasCity()) {
                $city = $location->city();
                $cities[$city->id()] = $city;
            }
        }
        return new Cities(...$cities);
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
     * List of unique cities extracted from all locations
     *
     * @return Cities
     */
    public function cities(): Cities
    {
        return $this->cities;
    }

    /**
     * @return array<array{
     *     id: int,
     *     name: string,
     *     fullname: string,
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
}

<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp;

use ArrayIterator;
use Countable;
use IteratorAggregate;

class Locations implements IteratorAggregate, Countable
{
    /** @var Location[] */
    private array $collection;

    private Cities $cities;

    public function __construct(Location ...$location)
    {
        $this->collection = $location;
        $this->cities = $this->extractUniqueCities(...$location);
    }

    public static function extractUniqueCities(Location ...$locations): Cities
    {
        // This method is static because it does not use $this
        $cities = [];
        foreach ($locations as $location) {
            if ($location->hasCity()) {
                $city = $location->city();
                if (! array_key_exists($city->id(), $cities)) {
                    $cities[$city->id()] = $location->city();
                }
            }
        }
        return new Cities(...$cities);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->collection);
    }

    public function count(): int
    {
        return count($this->collection);
    }

    /**
     * @return Cities|City[]
     */
    public function cities(): Cities
    {
        return $this->cities;
    }

    public function asArray(): array
    {
        $array = [];
        foreach ($this->collection as $location) {
            $array[] = $location->asArray();
        }
        return $array;
    }
}

<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Tests\Unit\Data;

use Eclipxe\SepomexPhp\Data\Location;
use Eclipxe\SepomexPhp\Data\Locations;
use Eclipxe\SepomexPhp\Data\LocationType;
use Eclipxe\SepomexPhp\Tests\TestCase;
use OutOfRangeException;

final class LocationsTest extends TestCase
{
    public function testLocationsIterator(): void
    {
        $locationType = new LocationType(1, 'Location type one');
        $objects = [
            new Location(1, 'First', $locationType),
            new Location(2, 'Second', $locationType),
            new Location(3, 'Third', $locationType),
        ];
        $locations = new Locations(...$objects);

        $this->assertSame($objects, iterator_to_array($locations));
    }

    public function testLocationsCount(): void
    {
        $locationType = new LocationType(1, 'Location type one');
        $objects = [
            $first = new Location(1, 'First', $locationType),
            new Location(2, 'Second', $locationType),
            new Location(3, 'Third', $locationType),
        ];
        $locations = new Locations(...$objects);

        $this->assertSame($first, $locations->first());
    }

    public function testCitiesByIndex(): void
    {
        $locationType = new LocationType(1, 'Location type one');
        $objects = [
            10 => new Location(1, 'First', $locationType),
            20 => new Location(2, 'Second', $locationType),
            30 => new Location(3, 'Third', $locationType),
            'foo' => new Location(99, 'Foo', $locationType),
        ];
        $locations = new Locations(...$objects);
        $objects = array_values($objects);
        foreach ($objects as $index => $cityObject) {
            $this->assertSame($cityObject, $locations->byIndex($index));
        }

        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage('Index out of bounds');
        $locations->byIndex(-1);
    }
}

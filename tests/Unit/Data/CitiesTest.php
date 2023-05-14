<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Tests\Unit\Data;

use Eclipxe\SepomexPhp\Data\Cities;
use Eclipxe\SepomexPhp\Data\City;
use Eclipxe\SepomexPhp\Tests\TestCase;
use JsonSerializable;
use OutOfRangeException;

final class CitiesTest extends TestCase
{
    public function testCitiesIterator(): void
    {
        $objects = [
            new City(1, 'First'),
            new City(2, 'Second'),
            new City(3, 'Third'),
        ];
        $cities = new Cities(...$objects);

        $this->assertSame($objects, iterator_to_array($cities));
    }

    public function testCitiesCount(): void
    {
        $objects = [
            new City(1, 'First'),
            new City(2, 'Second'),
            new City(3, 'Third'),
        ];
        $cities = new Cities(...$objects);

        $this->assertCount(3, $cities);
    }

    public function testFirst(): void
    {
        $objects = [
            $first = new City(1, 'First'),
            new City(2, 'Second'),
            new City(3, 'Third'),
        ];
        $cities = new Cities(...$objects);

        $this->assertSame($first, $cities->first());
    }

    public function testCitiesByIndex(): void
    {
        $objects = [
            10 => new City(1, 'First'),
            20 => new City(2, 'Second'),
            30 => new City(3, 'Third'),
            'foo' => new City(99, 'Foo'),
        ];
        $cities = new Cities(...$objects);
        $objects = array_values($objects);
        foreach ($objects as $index => $cityObject) {
            $this->assertSame($cityObject, $cities->byIndex($index));
        }

        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage('Index out of bounds');
        $cities->byIndex(-1);
    }

    public function testJsonSerialize(): void
    {
        $objects = [
            new City(1, 'First'),
            new City(2, 'Second'),
            new City(3, 'Third'),
        ];
        $cities = new Cities(...$objects);

        $this->assertInstanceOf(JsonSerializable::class, $cities);
        $this->assertSame($objects, $cities->jsonSerialize());
    }
}

<?php

declare(strict_types=1);

namespace SepomexPhpTests\Cases;

use SepomexPhp\District;
use SepomexPhpTests\TestCase;

class SearchDistrictsTest extends TestCase
{
    public function testSearchDistrictsCount()
    {
        $sepomex = $this->createSepomexPhp();

        $this->assertCount(0, $sepomex->searchDistricts('foo', 'bar'));
        $this->assertCount(4, $sepomex->searchDistricts('', 'chiap'));
        $this->assertCount(3, $sepomex->searchDistricts('a', 'chiap'));
        $this->assertCount(2, $sepomex->searchDistricts('a', 'chiap', 0, 2));
        $this->assertCount(1, $sepomex->searchDistricts('a', 'chiap', 1, 2));
        $this->assertCount(1, $sepomex->searchDistricts('Ocampo', 'chiap'));
        $this->assertCount(1, $sepomex->searchDistricts('Ocampo', ''));
    }

    public function testSearchDistrictsItem()
    {
        $sepomex = $this->createSepomexPhp();

        $list = $sepomex->searchDistricts('Ocampo', 'Chiapas');
        if (! isset($list[0])) {
            $this->fail('Expected 1 result');
        }

        $item = $list[0];
        $this->assertStringContainsString('Ocampo', $item->name());
        $this->assertStringContainsString('Chiapas', $item->state()->name());
    }

    public function testSearchDistrictsList()
    {
        $sepomex = $this->createSepomexPhp();

        $list = $sepomex->searchDistricts('', 'Chiapas');
        $this->assertContainsOnlyInstancesOf(District::class, $list);
    }
}

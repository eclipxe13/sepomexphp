<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Tests\Cases;

use DMS\PHPUnitExtensions\ArraySubset\Assert;
use Eclipxe\SepomexPhp\Tests\TestCase;

final class ZipCodeDataTest extends TestCase
{
    public function testGetZipCodeDataOnNotFound(): void
    {
        $sepomex = $this->createSepomexPhp();
        $this->assertNull($sepomex->getZipCodeData('00001'));
    }

    public function testZipCodeCheck(): void
    {
        $expectedZipCode = '88305';

        $sepomex = $this->createSepomexPhp();
        $zipcode = $sepomex->getZipCodeData($expectedZipCode);
        if (null === $zipcode) {
            $this->fail("Expected information of zip code $expectedZipCode was not found");
        }

        $data = $zipcode->asArray();

        Assert::assertArraySubset(['zipcode' => $expectedZipCode], $data);
        Assert::assertArraySubset(['state' => ['name' => 'Tamaulipas']], $data);
        Assert::assertArraySubset(['district' => ['name' => 'Miguel Alemán']], $data);
        Assert::assertArraySubset(['cities' => [['name' => 'Ciudad Miguel Alemán']]], $data);
        Assert::assertArraySubset(['locations' => [['name' => 'Adolfo López Mateos']]], $data);
        $this->assertCount(5, $data['locations']);
    }
}

<?php
declare(strict_types=1);
namespace SepomexPhpTests\Cases;

use SepomexPhpTests\TestCase;

class ZipCodeDataTest extends TestCase
{
    public function testGetZipCodeDataOnNotFound()
    {
        $sepomex = $this->createSepomexPhp();
        $this->assertNull($sepomex->getZipCodeData('00001'));
    }

    public function testZipCodeCheck()
    {
        $expectedZipCode = '88305';

        $sepomex = $this->createSepomexPhp();
        $zipcode = $sepomex->getZipCodeData($expectedZipCode);
        if (null === $zipcode) {
            $this->fail("Expected information of zip code $expectedZipCode was not found");
            return;
        }

        $data = $zipcode->asArray();

        $this->assertArraySubset(['zipcode' => $expectedZipCode], $data);
        $this->assertArraySubset(['state' => ['name' => 'Tamaulipas']], $data);
        $this->assertArraySubset(['district' => ['name' => 'Miguel Alemán']], $data);
        $this->assertArraySubset(['cities' => [['name' => 'Ciudad Miguel Alemán']]], $data);
        $this->assertArraySubset(['locations' => [['name' => 'Adolfo López Mateos']]], $data);
        $this->assertCount(5, $data['locations']);
    }
}

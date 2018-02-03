<?php
namespace SepomexPhpTests\Cases;

use SepomexPhp\PdoGateway\Gateway;
use SepomexPhp\SepomexPhp;
use SepomexPhpTests\TestCase;

class ZipCodeDataTest extends TestCase
{
    public function testZipCodeCheck()
    {
        $expectedZipCode = 88305;

        $sepomex = new SepomexPhp(new Gateway($this->pdo()));
        $zipcode = $sepomex->getZipCodeData($expectedZipCode);

        $data = $zipcode->asArray();

        $this->assertArraySubset(['zipcode' => $expectedZipCode], $data);
        $this->assertArraySubset(['state' => ['name' => 'Tamaulipas']], $data);
        $this->assertArraySubset(['district' => ['name' => 'Miguel Alemán']], $data);
        $this->assertArraySubset(['cities' => [['name' => 'Ciudad Miguel Alemán']]], $data);
        $this->assertArraySubset(['locations' => [['name' => 'Adolfo López Mateos']]], $data);
        $this->assertCount(5, $data['locations']);
    }
}

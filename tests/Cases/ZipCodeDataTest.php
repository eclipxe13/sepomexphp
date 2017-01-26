<?php
namespace SepomexPhpTests\Cases;

use PDO;
use SepomexPhp\City;
use SepomexPhp\District;
use SepomexPhp\Location;
use SepomexPhp\PdoGateway\Gateway;
use SepomexPhp\SepomexPhp;
use SepomexPhp\State;
use SepomexPhp\ZipCodeData;

class ZipCodeDataTest extends \PHPUnit_Framework_TestCase
{
    public function getPDO()
    {
        $dbfile = realpath(__DIR__ . '/../../assets/sepomex.db');
        if (! $dbfile) {
            $this->markTestIncomplete('Cannot find the sepomex.db');
        }
        return new PDO('sqlite:' . $dbfile, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    public function testZipCodeCheck()
    {
        $sepomex = new SepomexPhp(new Gateway($this->getPDO()));
        $zipcode = $sepomex->getZipCodeData(86000);
        $this->assertInstanceOf(ZipCodeData::class, $zipcode, 'The zip code was not found');
        $this->assertSame(86000, $zipcode->zipcode, 'The zipcode property does not match');
        $this->assertCount(3, $zipcode->locations, 'Not all locations were found');
        $this->assertInstanceOf(
            District::class,
            $zipcode->district,
            'The district was not received or is an invalid class'
        );
        $this->assertInstanceOf(State::class, $zipcode->state, 'The state was not received or is an invalid class');
        foreach ($zipcode->locations as $location) {
            $this->assertInstanceOf(Location::class, $location, 'One item in locations is not a Location');
            if (null !== $location->city) {
                $this->assertInstanceOf(City::class, $location->city, 'One city in locations is not a City');
            }
        }
        $this->assertNull($sepomex->getZipCodeData(-1), 'The not found value [NULL] was not returned');
    }
}

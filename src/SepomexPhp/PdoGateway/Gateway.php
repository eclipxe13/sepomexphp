<?php
/**
 * Created by PhpStorm.
 * User: eclipxe
 * Date: 9/08/15
 * Time: 10:51 PM
 */

namespace SepomexPhp\PdoGateway;

use SepomexPhp\DataGateway;

use PDO;

class Gateway implements DataGateway
{

    /**
     * @var PDO
     */
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @inheritdoc
     */
    public function getZipCodeData($zipcode)
    {
        $sql = 'select z.id as zipcode, d.id as iddistrict, d.name as districtname,'
            . ' s.id as idstate, s.name as statename'
            . ' from zipcodes as z'
            . ' inner join districts as d on (d.id = z.iddistrict)'
            . ' inner join states as s on (s.id = d.idstate)'
            . ' where (z.id = :zipcode)'
            . ';';
        $stmt = $this->pdo->prepare($sql);
        if (! $stmt->bindParam(':zipcode', $zipcode, PDO::PARAM_INT)) {
            throw new \RuntimeException("Cannot bind param :zipcode");
        }
        if (! $stmt->execute()) {
            throw new \RuntimeException("Cannot execute " . $stmt->queryString);
        }
        if (false === $data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return null;
        }
        return $data;
    }

    public function getLocationsFromZipCode($zipcode)
    {
        $sql = 'select l.id, l.name, t.name as type, c.id as idcity, c.name as cityname'
            . ' from zipcodes as z'
            . ' join locationzipcodes as lz on (lz.zipcode = z.id)'
            . ' join locations as l on (lz.idlocation = l.id)'
            . ' join locationtypes as t on (l.idlocationtype = t.id)'
            . ' left join cities as c on (l.idcity = c.id)'
            . ' where z.id = :zipcode'
            . ' order by l.name, t.name, c.name'
            . ';';
        $stmt = $this->pdo->prepare($sql);
        if (! $stmt->bindParam(':zipcode', $zipcode, PDO::PARAM_INT)) {
            throw new \RuntimeException("Cannot bind param :zipcode");
        }
        if (! $stmt->execute()) {
            throw new \RuntimeException("Cannot execute " . $stmt->queryString);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

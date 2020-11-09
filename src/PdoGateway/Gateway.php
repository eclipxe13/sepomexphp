<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\PdoGateway;

use Eclipxe\SepomexPhp\DataGatewayInterface;
use PDO;
use PDOStatement;
use RuntimeException;

class Gateway implements DataGatewayInterface
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getZipCodeData(string $zipcode): array
    {
        $sql = 'select z.id as zipcode, d.id as iddistrict, d.name as districtname,'
            . ' s.id as idstate, s.name as statename'
            . ' from zipcodes as z'
            . ' inner join districts as d on (d.id = z.iddistrict)'
            . ' inner join states as s on (s.id = d.idstate)'
            . ' where (z.id = :zipcode)'
            . ';';
        $stmt = $this->pdo->prepare($sql);
        return current($this->executeFetchAll($stmt, ['zipcode' => $zipcode])) ?: [];
    }

    public function getLocationsFromZipCode(string $zipcode): array
    {
        $sql = 'select l.id, l.name, t.id as idtype, t.name as typename, c.id as idcity, c.name as cityname'
            . ' from zipcodes as z'
            . ' join locationzipcodes as lz on (lz.zipcode = z.id)'
            . ' join locations as l on (lz.idlocation = l.id)'
            . ' join locationtypes as t on (l.idlocationtype = t.id)'
            . ' left join cities as c on (l.idcity = c.id)'
            . ' where z.id = :zipcode'
            . ' order by l.name, t.name, c.name'
            . ';';
        $stmt = $this->pdo->prepare($sql);
        return $this->executeFetchAll($stmt, ['zipcode' => $zipcode]);
    }

    public function searchDistricts(string $districtName, string $stateName, int $pageIndex, int $pageSize): array
    {
        $sql = 'select distinct d.id, d.name, s.id as idstate, s.name as statename'
            . ' from districts as d'
            . ' join states s on (d.idstate = s.id)'
            . ' where (d.name like :districtName)'
            . ' and (s.name like :stateName)'
            . ' order by s.name, d.name'
            . ' limit :pageIndex, :pageSize'
            . ';';
        $stmt = $this->pdo->prepare($sql);
        $params = [
            'districtName' => '%' . $districtName . '%',
            'stateName' => '%' . $stateName . '%',
            'pageIndex' => $pageIndex * $pageSize,
            'pageSize' => $pageSize,
        ];
        return $this->executeFetchAll($stmt, $params);
    }

    private function executeFetchAll(PDOStatement $statement, array $parameters): array
    {
        try {
            $execution = $statement->execute($parameters);
        } catch (\PDOException $exception) {
            throw new RuntimeException('Cannot execute ' . $statement->queryString, 0, $exception);
        }
        if (! $execution) {
            throw new RuntimeException('Cannot execute ' . $statement->queryString);
        }
        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}

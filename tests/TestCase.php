<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Tests;

use Eclipxe\SepomexPhp\PdoGateway\Gateway;
use Eclipxe\SepomexPhp\SepomexPhp;
use PDO;

class TestCase extends \PHPUnit\Framework\TestCase
{
    private ?\PDO $pdo = null;

    public static function utilAsset($filename)
    {
        return __DIR__ . '/assets/' . $filename;
    }

    protected function createSepomexPhp(): SepomexPhp
    {
        return new SepomexPhp(new Gateway($this->pdo($this->dbfile())));
    }

    public static function dbfile(): string
    {
        return static::utilAsset('test.db');
    }

    public function pdo(string $dbfile = '')
    {
        if (null === $this->pdo) {
            $this->pdo = $this->createPdo($dbfile);
        }
        return $this->pdo;
    }

    protected function createPdo(string $dbfile): PDO
    {
        if ('' === $dbfile) {
            $dbfile = $this->dbfile();
        }
        return new PDO('sqlite:' . $dbfile, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    protected function queryOne(string $sql, array $parameters = null)
    {
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($parameters);
        $fetched = $stmt->fetch(PDO::FETCH_NUM);
        if (is_array($fetched) && 1 === count($fetched)) {
            return $fetched[0];
        }
        return null;
    }
}

<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Tests;

use Eclipxe\SepomexPhp\PdoGateway\Gateway;
use Eclipxe\SepomexPhp\SepomexPhp;
use PDO;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    private ?PDO $pdo = null;

    public static function filePath(string $filename): string
    {
        return __DIR__ . '/_files/' . $filename;
    }

    protected function createSepomexPhp(): SepomexPhp
    {
        return new SepomexPhp(new Gateway($this->pdo($this->dbfile())));
    }

    public static function dbfile(): string
    {
        return static::filePath('test.db');
    }

    public function pdo(string $dbfile = ''): PDO
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

    /**
     * @param string $sql
     * @param array<string, string>|null $parameters
     * @return mixed|null
     */
    protected function queryOne(string $sql, array $parameters = null)
    {
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($parameters);
        $fetched = $stmt->fetch(PDO::FETCH_NUM);
        if (is_array($fetched) && isset($fetched[0])) {
            return $fetched[0];
        }
        return null;
    }
}

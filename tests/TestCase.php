<?php
namespace SepomexPhpTests;

use PDO;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var PDO */
    private $pdo;

    public static function utilAsset($filename)
    {
        return __DIR__ . '/assets/' . $filename;
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
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    protected function queryOne(string $sql, array $parameters = null)
    {
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($parameters);
        $fetched = $stmt->fetch(PDO::FETCH_NUM);
        if (is_array($fetched) && count($fetched) === 1) {
            return $fetched[0];
        }
        return null;
    }
}

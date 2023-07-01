<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Tests;

use Eclipxe\SepomexPhp\PdoDataGateway\PdoDataGateway;
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
        return new SepomexPhp(new PdoDataGateway($this->pdo()));
    }

    public function pdo(string $source = ''): PDO
    {
        if (null === $this->pdo) {
            $this->pdo = $this->createPdo($source);
        }
        return $this->pdo;
    }

    protected function createPdo(string $source): PDO
    {
        $source = $source ?: static::filePath('test.db');
        return new PDO('sqlite:' . $source, options: [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    /**
     * @param array<string, string>|null $parameters
     * @return scalar|null
     * @noinspection PhpMissingReturnTypeInspection
     */
    protected function queryOne(string $sql, array $parameters = null)
    {
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($parameters);
        $fetched = $stmt->fetch(PDO::FETCH_NUM);
        if (is_array($fetched) && isset($fetched[0]) && is_scalar($fetched[0])) {
            return $fetched[0];
        }
        return null;
    }
}

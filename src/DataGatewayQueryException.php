<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp;

use RuntimeException;
use Throwable;

final class DataGatewayQueryException extends RuntimeException
{
    private string $query;

    /** @var array<string, scalar|null> */
    private array $parameters;

    /**
     * @param string $message
     * @param string $query
     * @param array<string, scalar|null> $parameters
     * @param Throwable|null $previous
     */
    public function __construct(string $message, string $query, array $parameters, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->query = $query;
        $this->parameters = $parameters;
    }

    /**
     * @param string $query
     * @param array<string, scalar|null> $parameters
     * @param Throwable|null $previous
     * @return self
     */
    public static function new(string $query, array $parameters, Throwable $previous = null): self
    {
        $message = 'Unable to execute statement: ' . $query;
        return new self($message, $query, $parameters, $previous);
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return array<string, scalar|null>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}

<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp;

use RuntimeException;
use Throwable;

final class DataGatewayQueryException extends RuntimeException
{
    private string $query;

    private array $parameters;

    public function __construct(string $message, string $query, array $parameters, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->query = $query;
        $this->parameters = $parameters;
    }

    public static function new(string $query, array $parameters, Throwable $previous = null): self
    {
        $message = 'Unable to execute statement: ' . $query;
        return new self($message, $query, $parameters, $previous);
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}

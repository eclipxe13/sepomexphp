<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp;

use RuntimeException;
use Throwable;

final class DataGatewayQueryException extends RuntimeException
{
    /**
     * @param array<string, scalar|null> $parameters
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message,
        private readonly string $query,
        private readonly array $parameters,
        Throwable $previous = null
    ) {
        parent::__construct($message, previous: $previous);
    }

    /**
     * @param array<string, scalar|null> $parameters
     * @param Throwable|null $previous
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

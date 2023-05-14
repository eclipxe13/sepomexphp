<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Internal;

use InvalidArgumentException;

/** @internal */
final class DataValidation
{
    /**
     * @throws InvalidArgumentException when id is equal or less than zero
     */
    public static function validateArgumentId(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('Id property cannot be less than or equal to zero');
        }
    }

    /**
     * @throws InvalidArgumentException when name is an empty string
     */
    public static function validateName(string $name): void
    {
        if ('' === $name) {
            throw new InvalidArgumentException('Name property cannot be empty');
        }
    }

    /**
     * @throws InvalidArgumentException when zipcode is not 4 or 5 digits
     */
    public static function validateZipCode(string $zipcode): void
    {
        if (! preg_match('/^\d{4,5}$/', $zipcode)) {
            throw new InvalidArgumentException('Zipcode must be 4 to 5 digits');
        }
    }
}

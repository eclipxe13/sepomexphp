<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Traits;

use Eclipxe\SepomexPhp\City;
use LogicException;

trait PropertyCityTrait
{
    private ?City $city = null;

    public function city(): City
    {
        if (! $this->city instanceof City) {
            throw new LogicException('Try to access city when no exists');
        }
        return $this->city;
    }

    public function hasCity(): bool
    {
        return ($this->city instanceof City);
    }

    protected function setCity(City $city = null)
    {
        $this->city = $city;
    }
}

<?php

declare(strict_types=1);

namespace SepomexPhp\Traits;

use SepomexPhp\District;

trait PropertyDistrictTrait
{
    /** @var District|null */
    private $district;

    public function district(): District
    {
        if (! $this->district instanceof District) {
            throw new \LogicException('Try to access district when no exists');
        }
        return $this->district;
    }

    public function hasDistrict(): bool
    {
        return ($this->district instanceof District);
    }

    protected function setDistrict(District $district = null)
    {
        $this->district = $district;
    }
}

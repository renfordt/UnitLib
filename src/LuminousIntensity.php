<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Represents luminous intensity with support for SI units.
 *
 * Native unit: candela (cd)
 * Supports: cd, mcd, kcd, etc.
 */
class LuminousIntensity extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'cd';
    }

    protected function initialise(): void
    {
        $candela = new UnitOfMeasurement('cd', 1);
        $candela->addAlias('candela');
        $candela->addAlias('candelas');
        $this->addUnit($candela);
    }
}

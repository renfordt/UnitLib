<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Represents electric voltage with support for SI units.
 *
 * Native unit: volt (V)
 * Supports: V, mV, μV, kV, MV, GV, etc.
 */
class Voltage extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'V';
    }

    protected function initialise(): void
    {
        $volt = new UnitOfMeasurement('V', 1);
        $volt->addAlias('volt');
        $volt->addAlias('volts');
        $this->addUnit($volt);
    }
}

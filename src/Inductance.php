<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Represents electrical inductance with support for SI units.
 *
 * Native unit: henry (H)
 * Formula: L = Φ/I (inductance = magnetic flux / current)
 */
class Inductance extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'H';
    }

    protected function initialise(): void
    {
        // SI base unit (henry)
        $henry = new UnitOfMeasurement('H', 1);
        $henry->addAlias('henry');
        $henry->addAlias('henries');
        $henry->addAlias('henrys');
        $this->addUnit($henry);
    }
}

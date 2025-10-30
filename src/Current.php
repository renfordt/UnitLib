<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Represents electric current with support for SI units.
 *
 * Native unit: ampere (A)
 * Supports: A, mA, μA, nA, kA, MA, etc.
 */
class Current extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'A';
    }

    protected function initialise(): void
    {
        $ampere = new UnitOfMeasurement('A', 1);
        $ampere->addAlias('ampere');
        $ampere->addAlias('amperes');
        $ampere->addAlias('amp');
        $ampere->addAlias('amps');
        $this->addUnit($ampere);
    }
}

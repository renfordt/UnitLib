<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Represents magnetic flux with support for SI units.
 *
 * Native unit: weber (Wb)
 * Formula: Φ = V × t (magnetic flux = voltage × time)
 */
class MagneticFlux extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'Wb';
    }

    /**
     * Create MagneticFlux from Voltage and Time (Φ = V × t).
     */
    public static function fromVoltageAndTime(Voltage $voltage, Time $time): self
    {
        $flux = $voltage->nativeValue * $time->nativeValue;

        return new self($flux, 'Wb');
    }

    protected function initialise(): void
    {
        // SI base unit (weber)
        $weber = new UnitOfMeasurement('Wb', 1);
        $weber->addAlias('weber');
        $weber->addAlias('webers');
        $this->addUnit($weber);

        // Maxwell (CGS unit)
        $maxwell = new UnitOfMeasurement('Mx', 1e-8);
        $maxwell->addAlias('maxwell');
        $maxwell->addAlias('maxwells');
        $this->addUnit($maxwell);
    }
}

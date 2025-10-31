<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Represents electric current with support for SI units.
 *
 * Native unit: ampere (A)
 * Supports: A, mA, μA, nA, kA, MA, etc.
 *
 * Ohm's law relationships:
 * - Voltage = Current × Resistance (V = I × R)
 * - Current = Voltage / Resistance (I = V / R)
 * - Resistance = Voltage / Current (R = V / I)
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

    /**
     * Static factory method: Calculate current from voltage and resistance.
     * Uses Ohm's law: I = V / R
     */
    public static function fromVoltageAndResistance(Voltage $voltage, Resistance $resistance): self
    {
        /** @var self */
        return $voltage->divide($resistance);
    }
}

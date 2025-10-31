<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Represents electric voltage with support for SI units.
 *
 * Native unit: volt (V)
 * Supports: V, mV, μV, kV, MV, GV, etc.
 *
 * Ohm's law relationships:
 * - Voltage = Current × Resistance (V = I × R)
 * - Current = Voltage / Resistance (I = V / R)
 * - Resistance = Voltage / Current (R = V / I)
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

    /**
     * Static factory method: Calculate voltage from current and resistance.
     * Uses Ohm's law: V = I × R
     */
    public static function fromCurrentAndResistance(Current $current, Resistance $resistance): self
    {
        /** @var self */
        return $current->multiply($resistance);
    }
}

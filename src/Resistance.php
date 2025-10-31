<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Class Resistance
 *
 * Represents electrical resistance.
 * Native unit: ohm (Ω)
 *
 * The ohm is the SI unit of electrical resistance, defined as the resistance
 * between two points when a constant potential difference of 1 volt produces
 * a current of 1 ampere (Ohm's law: R = V/I).
 *
 * Supported units:
 * - Ohm (Ω, ohm, ohms) and metric prefixes (kΩ, MΩ, mΩ, etc.)
 *
 * Ohm's law relationships:
 * - Voltage = Current × Resistance (V = I × R)
 * - Current = Voltage / Resistance (I = V / R)
 * - Resistance = Voltage / Current (R = V / I)
 *
 * @package Renfordt\UnitLib
 */
class Resistance extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'Ω';
    }

    protected function initialise(): void
    {
        // Ohm is the native unit
        $ohm = new UnitOfMeasurement('Ω', 1);
        $ohm->addAlias('ohm');
        $ohm->addAlias('ohms');
        $this->addUnit($ohm);
    }

    /**
     * Static factory method: Calculate resistance from voltage and current.
     * Uses Ohm's law: R = V / I
     */
    public static function fromVoltageAndCurrent(Voltage $voltage, Current $current): self
    {
        /** @var self */
        return $voltage->divide($current);
    }
}

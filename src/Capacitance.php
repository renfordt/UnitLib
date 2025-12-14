<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Represents electrical capacitance with support for SI units.
 *
 * Native unit: farad (F)
 * Formula: C = Q/V (capacitance = charge / voltage)
 */
class Capacitance extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'F';
    }

    /**
     * Create Capacitance from Charge and Voltage (C = Q/V).
     */
    public static function fromChargeAndVoltage(Charge $charge, Voltage $voltage): self
    {
        if ($voltage->nativeValue === 0.0) {
            throw new \InvalidArgumentException('Cannot calculate capacitance with zero voltage');
        }

        $capacitance = $charge->nativeValue / $voltage->nativeValue;

        return new self($capacitance, 'F');
    }

    protected function initialise(): void
    {
        // SI base unit (farad)
        $farad = new UnitOfMeasurement('F', 1);
        $farad->addAlias('farad');
        $farad->addAlias('farads');
        $this->addUnit($farad);
    }
}

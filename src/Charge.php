<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Represents electric charge with support for SI units.
 *
 * Native unit: coulomb (C)
 * Formula: Q = I × t (charge = current × time)
 */
class Charge extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'C';
    }

    /**
     * Create Charge from Current and Time (Q = I × t).
     */
    public static function fromCurrentAndTime(Current $current, Time $time): self
    {
        $charge = $current->nativeValue * $time->nativeValue;

        return new self($charge, 'C');
    }

    protected function initialise(): void
    {
        // SI base unit (coulomb)
        $coulomb = new UnitOfMeasurement('C', 1);
        $coulomb->addAlias('coulomb');
        $coulomb->addAlias('coulombs');
        $this->addUnit($coulomb);

        // Elementary charge
        $elementaryCharge = new UnitOfMeasurement('e', 1.602176634e-19);
        $elementaryCharge->addAlias('elementary charge');
        $this->addUnit($elementaryCharge);

        // Ampere-hour
        $ampereHour = new UnitOfMeasurement('Ah', 3600);
        $ampereHour->addAlias('A⋅h');
        $ampereHour->addAlias('ampere-hour');
        $ampereHour->addAlias('amp-hour');
        $this->addUnit($ampereHour);

        // Milliampere-hour (common in batteries)
        $milliampereHour = new UnitOfMeasurement('mAh', 3.6);
        $milliampereHour->addAlias('milliampere-hour');
        $this->addUnit($milliampereHour);
    }
}

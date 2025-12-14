<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Represents torque (moment of force) with support for SI units.
 *
 * Native unit: newton-meter (N⋅m)
 * Formula: τ = F × r (torque = force × distance)
 *
 * Note: While dimensionally equivalent to Energy (both N⋅m),
 * torque is conceptually distinct and should not be confused with work/energy.
 */
class Torque extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'N⋅m';
    }

    /**
     * Create Torque from Force and Length (τ = F × r).
     */
    public static function fromForceAndLength(Force $force, Length $length): self
    {
        $torque = $force->nativeValue * $length->nativeValue;

        return new self($torque, 'N⋅m');
    }

    protected function initialise(): void
    {
        // SI base unit
        $newtonMeter = new UnitOfMeasurement('N⋅m', 1);
        $newtonMeter->addAlias('Nm');
        $newtonMeter->addAlias('N·m');
        $newtonMeter->addAlias('newton-meter');
        $newtonMeter->addAlias('newton-metre');
        $this->addUnit($newtonMeter);

        // Pound-force foot
        $lbfFt = new UnitOfMeasurement('lbf⋅ft', 1.3558179483314);
        $lbfFt->addAlias('lb-ft');
        $lbfFt->addAlias('lbf-ft');
        $lbfFt->addAlias('pound-force foot');
        $this->addUnit($lbfFt);

        // Pound-force inch
        $lbfIn = new UnitOfMeasurement('lbf⋅in', 0.1129848290276);
        $lbfIn->addAlias('lb-in');
        $lbfIn->addAlias('lbf-in');
        $lbfIn->addAlias('pound-force inch');
        $this->addUnit($lbfIn);

        // Dyne-centimeter
        $dyneCm = new UnitOfMeasurement('dyn⋅cm', 0.0000001);
        $dyneCm->addAlias('dyne-centimeter');
        $this->addUnit($dyneCm);
    }
}

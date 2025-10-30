<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

class Force extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'N';
    }

    /**
     * Create a Force from Mass × Acceleration (Newton's second law: F = ma).
     */
    public static function fromMassAndAcceleration(Mass $mass, Acceleration $acceleration): self
    {
        /** @var self */
        return $mass->multiply($acceleration);
    }

    protected function initialise(): void
    {
        // SI base unit (Newton)
        $newton = new UnitOfMeasurement('N', 1);
        $newton->addAlias('newton');
        $newton->addAlias('newtons');
        $this->addUnit($newton);

        // CGS unit
        $dyne = new UnitOfMeasurement('dyn', 0.00001);
        $dyne->addAlias('dyne');
        $dyne->addAlias('dynes');
        $this->addUnit($dyne);

        // Gravitational units
        $kilogramForce = new UnitOfMeasurement('kgf', 9.80665);
        $kilogramForce->addAlias('kg-force');
        $kilogramForce->addAlias('kilogram-force');
        $kilogramForce->addAlias('kilopond');
        $this->addUnit($kilogramForce);

        // Imperial units
        $poundForce = new UnitOfMeasurement('lbf', 4.4482216152605);
        $poundForce->addAlias('lb-force');
        $poundForce->addAlias('pound-force');
        $this->addUnit($poundForce);

        $poundal = new UnitOfMeasurement('pdl', 0.138254954376);
        $poundal->addAlias('poundal');
        $poundal->addAlias('poundals');
        $this->addUnit($poundal);
    }
}

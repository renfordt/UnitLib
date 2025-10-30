<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

class Acceleration extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'm/s²';
    }

    /**
     * Create an Acceleration from Velocity and Time.
     */
    public static function fromVelocityAndTime(Velocity $velocity, Time $time): self
    {
        /** @var self */
        return $velocity->divide($time);
    }

    /**
     * Create an Acceleration from Length and Time squared.
     */
    public static function fromLengthAndTime(Length $length, Time $time): self
    {
        if ($time->nativeValue === 0.0) {
            throw new \InvalidArgumentException('Cannot divide by zero time');
        }

        $result = $length->nativeValue / ($time->nativeValue ** 2);

        return new self($result, 'm/s²');
    }

    protected function initialise(): void
    {
        // Metric base unit (SI)
        $meterPerSecondSquared = new UnitOfMeasurement('m/s²', 1);
        $meterPerSecondSquared->addAlias('m/s2');
        $meterPerSecondSquared->addAlias('meter per second squared');
        $meterPerSecondSquared->addAlias('meters per second squared');
        $meterPerSecondSquared->addAlias('metre per second squared');
        $meterPerSecondSquared->addAlias('metres per second squared');
        $this->addUnit($meterPerSecondSquared);

        // Standard gravity (g₀)
        $standardGravity = new UnitOfMeasurement('g', 9.80665);
        $standardGravity->addAlias('g₀');
        $standardGravity->addAlias('standard gravity');
        $this->addUnit($standardGravity);

        // Feet per second squared
        $feetPerSecondSquared = new UnitOfMeasurement('ft/s²', 0.3048);
        $feetPerSecondSquared->addAlias('ft/s2');
        $feetPerSecondSquared->addAlias('foot per second squared');
        $feetPerSecondSquared->addAlias('feet per second squared');
        $this->addUnit($feetPerSecondSquared);

        // Galileo (Gal) - commonly used in geophysics
        $gal = new UnitOfMeasurement('Gal', 0.01);
        $gal->addAlias('galileo');
        $this->addUnit($gal);
    }
}

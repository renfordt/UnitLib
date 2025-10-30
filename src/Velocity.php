<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

class Velocity extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'm/s';
    }

    /**
     * Create a Velocity from Length and Time.
     */
    public static function fromLengthAndTime(Length $length, Time $time): self
    {
        /** @var self */
        return $length->divide($time);
    }

    protected function initialise(): void
    {
        // Metric base unit (SI)
        $meterPerSecond = new UnitOfMeasurement('m/s', 1);
        $meterPerSecond->addAlias('meter per second');
        $meterPerSecond->addAlias('meters per second');
        $meterPerSecond->addAlias('metre per second');
        $meterPerSecond->addAlias('metres per second');
        $meterPerSecond->addAlias('mps');
        $this->addUnit($meterPerSecond);

        // Kilometer per hour
        $kilometerPerHour = new UnitOfMeasurement('km/h', 0.277777778);
        $kilometerPerHour->addAlias('kmh');
        $kilometerPerHour->addAlias('kph');
        $kilometerPerHour->addAlias('kilometer per hour');
        $kilometerPerHour->addAlias('kilometers per hour');
        $kilometerPerHour->addAlias('kilometre per hour');
        $kilometerPerHour->addAlias('kilometres per hour');
        $this->addUnit($kilometerPerHour);

        // Miles per hour
        $milesPerHour = new UnitOfMeasurement('mph', 0.44704);
        $milesPerHour->addAlias('mi/h');
        $milesPerHour->addAlias('mile per hour');
        $milesPerHour->addAlias('miles per hour');
        $this->addUnit($milesPerHour);

        // Feet per second
        $feetPerSecond = new UnitOfMeasurement('ft/s', 0.3048);
        $feetPerSecond->addAlias('fps');
        $feetPerSecond->addAlias('foot per second');
        $feetPerSecond->addAlias('feet per second');
        $this->addUnit($feetPerSecond);

        // Knots (nautical miles per hour)
        $knot = new UnitOfMeasurement('kt', 0.514444444);
        $knot->addAlias('kn');
        $knot->addAlias('knot');
        $knot->addAlias('knots');
        $knot->addAlias('nautical mile per hour');
        $knot->addAlias('nautical miles per hour');
        $this->addUnit($knot);
    }
}

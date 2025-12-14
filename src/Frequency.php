<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Represents frequency with support for SI units.
 *
 * Native unit: hertz (Hz)
 * Common units: Hz, kHz, MHz, GHz, THz
 * Other units: RPM (revolutions per minute), BPM (beats per minute)
 */
class Frequency extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'Hz';
    }

    /**
     * Create Frequency from Time period (f = 1/T).
     */
    public static function fromTimePeriod(Time $period): self
    {
        if ($period->nativeValue === 0.0) {
            throw new \InvalidArgumentException('Cannot calculate frequency from zero time period');
        }

        return new self(1 / $period->nativeValue, 'Hz');
    }

    protected function initialise(): void
    {
        // SI base unit
        $hertz = new UnitOfMeasurement('Hz', 1);
        $hertz->addAlias('hertz');
        $hertz->addAlias('hz');
        $this->addUnit($hertz);

        // Revolutions per minute
        $rpm = new UnitOfMeasurement('RPM', 1 / 60);
        $rpm->addAlias('rpm');
        $rpm->addAlias('revolutions per minute');
        $rpm->addAlias('rev/min');
        $this->addUnit($rpm);

        // Beats per minute
        $bpm = new UnitOfMeasurement('BPM', 1 / 60);
        $bpm->addAlias('bpm');
        $bpm->addAlias('beats per minute');
        $this->addUnit($bpm);

        // Radians per second
        $radPerSec = new UnitOfMeasurement('rad/s', 1 / (2 * M_PI));
        $radPerSec->addAlias('radians per second');
        $this->addUnit($radPerSec);
    }
}

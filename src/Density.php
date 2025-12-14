<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Represents density with support for SI units.
 *
 * Native unit: kilogram per cubic meter (kg/m³)
 * Formula: ρ = m/V (density = mass / volume)
 */
class Density extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'kg/m³';
    }

    /**
     * Create Density from Mass and Volume (ρ = m/V).
     */
    public static function fromMassAndVolume(Mass $mass, Volume $volume): self
    {
        if ($volume->nativeValue === 0.0) {
            throw new \InvalidArgumentException('Cannot calculate density with zero volume');
        }

        // Mass native unit is grams, Volume native unit is m³
        // kg/m³ = (g / 1000) / m³ = g / (1000 * m³)
        $density = $mass->nativeValue / (1000 * $volume->nativeValue);

        return new self($density, 'kg/m³');
    }

    protected function initialise(): void
    {
        // SI base unit
        $kgPerM3 = new UnitOfMeasurement('kg/m³', 1);
        $kgPerM3->addAlias('kg/m3');
        $kgPerM3->addAlias('kilogram per cubic meter');
        $kgPerM3->addAlias('kilogram per cubic metre');
        $this->addUnit($kgPerM3);

        // Grams per cubic centimeter
        $gPerCm3 = new UnitOfMeasurement('g/cm³', 1000);
        $gPerCm3->addAlias('g/cm3');
        $gPerCm3->addAlias('gram per cubic centimeter');
        $this->addUnit($gPerCm3);

        // Grams per liter
        $gPerL = new UnitOfMeasurement('g/L', 1);
        $gPerL->addAlias('gram per liter');
        $gPerL->addAlias('gram per litre');
        $this->addUnit($gPerL);

        // Pounds per cubic foot
        $lbPerFt3 = new UnitOfMeasurement('lb/ft³', 16.0185);
        $lbPerFt3->addAlias('lb/ft3');
        $lbPerFt3->addAlias('pound per cubic foot');
        $this->addUnit($lbPerFt3);

        // Pounds per gallon (US)
        $lbPerGal = new UnitOfMeasurement('lb/gal', 119.826);
        $lbPerGal->addAlias('pound per gallon');
        $lbPerGal->addAlias('ppg');
        $this->addUnit($lbPerGal);
    }
}

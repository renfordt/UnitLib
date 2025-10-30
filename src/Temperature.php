<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

class Temperature extends PhysicalQuantity
{
    protected function initialise(): void
    {
        // SI base unit (Kelvin)
        $kelvin = new UnitOfMeasurement('K', 1);
        $kelvin->addAlias('kelvin');
        $kelvin->addAlias('Kelvin');
        $this->addUnit($kelvin);

        // Celsius (offset-based, handled specially)
        $celsius = new UnitOfMeasurement('°C', 1);
        $celsius->addAlias('C');
        $celsius->addAlias('celsius');
        $celsius->addAlias('Celsius');
        $this->addUnit($celsius);

        // Fahrenheit (offset-based, handled specially)
        $fahrenheit = new UnitOfMeasurement('°F', 1);
        $fahrenheit->addAlias('F');
        $fahrenheit->addAlias('fahrenheit');
        $fahrenheit->addAlias('Fahrenheit');
        $this->addUnit($fahrenheit);

        // Rankine
        $rankine = new UnitOfMeasurement('°R', 0.555555555555556);
        $rankine->addAlias('R');
        $rankine->addAlias('rankine');
        $rankine->addAlias('Rankine');
        $this->addUnit($rankine);
    }

    protected function convertToNativeUnit(float $value, string $unitName): float
    {
        return match($unitName) {
            'K', 'kelvin', 'Kelvin' => $value,
            '°C', 'C', 'celsius', 'Celsius' => $value + 273.15,
            '°F', 'F', 'fahrenheit', 'Fahrenheit' => ($value - 32) * 5 / 9 + 273.15,
            '°R', 'R', 'rankine', 'Rankine' => $value * 5 / 9,
            default => throw new \InvalidArgumentException("Unknown temperature unit: {$unitName}")
        };
    }

    public function toUnit(string|UnitOfMeasurement $unit): float
    {
        $unitName = is_string($unit) ? $unit : $unit->name;

        // Convert from Kelvin to target unit
        return match($unitName) {
            'K', 'kelvin', 'Kelvin' => $this->nativeValue,
            '°C', 'C', 'celsius', 'Celsius' => $this->nativeValue - 273.15,
            '°F', 'F', 'fahrenheit', 'Fahrenheit' => ($this->nativeValue - 273.15) * 9 / 5 + 32,
            '°R', 'R', 'rankine', 'Rankine' => $this->nativeValue * 9 / 5,
            default => parent::toUnit($unit)
        };
    }
}

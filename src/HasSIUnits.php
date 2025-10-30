<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

use InvalidArgumentException;

trait HasSIUnits
{
    /**
     * Map of metric prefixes to their powers of 10.
     *
     * @var array<string, int>
     */
    private array $metricPrefixes {
        get {
            return [
                '' => 0,       // base unit (meter)
                'k' => 3,      // kilo (10^3)
                'h' => 2,      // hecto (10^2)
                'da' => 1,     // deca (10^1)
                'd' => -1,     // deci (10^-1)
                'c' => -2,     // centi (10^-2)
                'm' => -3,     // milli (10^-3)
                'μ' => -6,     // micro (10^-6)
                'n' => -9,     // nano (10^-9)
                'p' => -12,    // pico (10^-12)
                'f' => -15,    // femto (10^-15)
                'a' => -18,    // atto (10^-18)
                'z' => -21,    // zepto (10^-21)
                'y' => -24,    // yocto (10^-24)
                'r' => -27,    // ronto (10^-27)
                'q' => -30,    // quecto (10^-30)
                'M' => 6,      // mega (10^6)
                'G' => 9,      // giga (10^9)
                'T' => 12,     // tera (10^12)
                'P' => 15,     // peta (10^15)
                'E' => 18,     // exa (10^18)
                'Z' => 21,     // zetta (10^21)
                'Y' => 24,     // yotta (10^24)
                'R' => 27,     // ronna (10^27)
                'Q' => 30,     // quetta (10^30)
            ];
        }
    }

    /**
     * Converts a value from one metric unit to another.
     * Automatically detects power (m, m², m³, etc.) and applies correct conversion.
     *
     * @param  float  $value  The value to convert.
     * @param  string  $fromUnit  The unit to convert from (e.g., "km", "cm²", "mm³").
     * @param  string  $toUnit  The unit to convert to (e.g., "m", "m²", "m³").
     * @return float The converted value.
     *
     * @throws InvalidArgumentException If the unit prefixes are invalid.
     */
    public function convertSIUnit(float $value, string $fromUnit, string $toUnit): float
    {
        // Extract the prefixes and power
        [$fromPrefix, $fromPower] = $this->extractPrefixAndPower($fromUnit);
        [$toPrefix, $toPower] = $this->extractPrefixAndPower($toUnit);

        // Check if units were successfully parsed (non-null prefix or valid base unit)
        $fromValid = $this->isValidSIUnit($fromUnit, $fromPrefix, $fromPower);
        $toValid = $this->isValidSIUnit($toUnit, $toPrefix, $toPower);

        if (! $fromValid || ! $toValid) {
            throw new InvalidArgumentException('Invalid metric unit provided');
        }

        // Verify both units have the same power
        if ($fromPower !== $toPower) {
            throw new InvalidArgumentException('Cannot convert between units of different powers');
        }

        $metricPrefixes = $this->metricPrefixes;

        if (! array_key_exists($fromPrefix, $metricPrefixes) || ! array_key_exists($toPrefix, $metricPrefixes)) {
            throw new InvalidArgumentException('Invalid metric unit provided');
        }

        // Calculate the exponent difference
        $fromExponent = $metricPrefixes[$fromPrefix];
        $toExponent = $metricPrefixes[$toPrefix];
        $linearFactor = 10 ** ($fromExponent - $toExponent);

        // Apply power (for area: power=2, for volume: power=3, for linear: power=1)
        $conversionFactor = $linearFactor ** $fromPower;

        // Convert the value
        return $value * $conversionFactor;
    }

    /**
     * Get the base unit symbol for SI prefix extraction.
     * Override this method in child classes for different base units.
     *
     * @return string The base unit symbol (e.g., 'm', 'g', 's', 'N', 'J', 'W', 'Pa')
     */
    protected function getSIBaseUnit(): string
    {
        // Default to meter for backward compatibility
        return 'm';
    }

    /**
     * Validates if a unit string is a valid SI unit.
     *
     * @param  string  $unit  The full unit string.
     * @param  string  $prefix  The extracted prefix.
     * @param  int  $power  The extracted power.
     * @return bool True if valid SI unit, false otherwise.
     */
    private function isValidSIUnit(string $unit, string $prefix, int $power): bool
    {
        $baseUnit = $this->getSIBaseUnit();

        // Check if unit ends with base unit (with or without power notation)
        if (str_ends_with($unit, $baseUnit)) {
            return true;
        }

        // For units with power, also check numeric notation (e.g., "m2" vs "m²")
        if ($power > 1) {
            $baseWithoutPower = preg_replace('/[²³⁴⁵⁶⁷⁸⁹2-9]$/', '', $baseUnit);
            $numericPowerUnit = $baseWithoutPower . $power;
            if (str_ends_with($unit, $numericPowerUnit)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extracts the prefix and power from a unit string.
     * Examples: "km" => ['k', 1], "cm²" => ['c', 2], "mm³" => ['m', 3]
     *
     * @param  string  $unit  The unit string (e.g., "mm", "km²", "cm³").
     * @return array{string, int} [prefix, power]
     */
    private function extractPrefixAndPower(string $unit): array
    {
        $baseUnit = $this->getSIBaseUnit();

        // Detect power from the base unit itself
        $basePower = 1;
        if (str_contains($baseUnit, '²') || str_ends_with($baseUnit, '2')) {
            $basePower = 2;
        } elseif (str_contains($baseUnit, '³') || str_ends_with($baseUnit, '3')) {
            $basePower = 3;
        }

        // Check if unit ends with base unit
        if (str_ends_with($unit, $baseUnit)) {
            $prefix = substr($unit, 0, -strlen($baseUnit));
            return [$prefix, $basePower];
        }

        // If base unit has power notation but unit doesn't match exactly,
        // try matching with numeric notation (e.g., "m2" vs "m²")
        if ($basePower > 1) {
            // Extract base without power symbol
            $baseWithoutPower = preg_replace('/[²³⁴⁵⁶⁷⁸⁹2-9]$/', '', $baseUnit);
            // Try matching with numeric notation
            $numericPowerUnit = $baseWithoutPower . $basePower;
            if (str_ends_with($unit, $numericPowerUnit)) {
                $prefix = substr($unit, 0, -strlen($numericPowerUnit));
                return [$prefix, $basePower];
            }
        }

        return ['', 1];
    }
}

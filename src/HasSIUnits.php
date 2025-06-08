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
    private array $metricPrefixes = [
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

    /**
     * Converts a value from one metric unit to another.
     *
     * @param  string  $toUnit  The unit to convert to (e.g., "km").
     * @return float The converted value.
     *
     * @throws InvalidArgumentException If the unit prefixes are invalid.
     */
    public function convertSIUnit(float $value, string $fromUnit, string $toUnit): float
    {
        // Extract the prefixes (e.g., "mm" becomes 'm', "km" becomes 'k').
        $fromPrefix = $this->extractPrefix($fromUnit);
        $toPrefix = $this->extractPrefix($toUnit);

        if (! array_key_exists($fromPrefix, $this->metricPrefixes) || ! array_key_exists($toPrefix, $this->metricPrefixes)) {
            throw new InvalidArgumentException('Invalid metric unit provided');
        }

        // Calculate the exponent difference
        $fromExponent = $this->metricPrefixes[$fromPrefix];
        $toExponent = $this->metricPrefixes[$toPrefix];
        $conversionFactor = 10 ** ($fromExponent - $toExponent);

        // Convert the value
        return $value * $conversionFactor;
    }

    /**
     * Extracts the prefix (the string before the base unit) from a given unit.
     *
     * @param  string  $unit  The unit string (e.g., "mm", "km").
     * @return string The extracted prefix (e.g., 'm', 'k').
     */
    private function extractPrefix(string $unit): string
    {
        // Assume the base unit is always "m" (for simplicity).
        if (str_ends_with($unit, 'm')) {
            return substr($unit, 0, -1); // Remove the base unit
        }

        // Handle invalid cases
        return '';
    }
}

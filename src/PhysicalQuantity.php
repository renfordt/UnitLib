<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

abstract class PhysicalQuantity implements \Stringable
{
    public readonly float $nativeValue;

    /**
     * Represents the native unit associated with a specific context or measurement.
     *
     * This property is used to define and store the standard or default unit relevant
     * to the context in which it is utilized.
     */
    public readonly UnitOfMeasurement $nativeUnit;

    /**
     * List of measurement units available in the context of the application or system.
     *
     * @var array<UnitOfMeasurement> Array containing unit definitions, typically stored as string keys or objects representing measurement units.
     */
    protected array $units;

    /**
     * The original unit of measurement associated with the value.
     *
     * @var UnitOfMeasurement Represents the unit either as an object or its string representation.
     */
    protected readonly UnitOfMeasurement $originalUnit;

    /**
     * Constructs a new instance of the class and initializes the object with the provided value and unit.
     *
     * @param  float  $originalValue  The original value to be initialized.
     * @param  string|UnitOfMeasurement  $unit  The unit associated with the original value.
     * @return void
     */
    public function __construct(public readonly float $originalValue, string|UnitOfMeasurement $unit)
    {
        $this->initialise();

        $unitName = $unit instanceof UnitOfMeasurement ? $unit->name : $unit;

        // Set the native unit (first unit in the array)
        $this->nativeUnit = $this->units[0];

        // Determine the original unit and native value
        $foundUnit = null;
        $calculatedNativeValue = 0.0;

        // Try to find the unit in the registered units first
        try {
            $foundUnit = $this->findUnit($unitName);

            // Allow child classes to override the conversion logic
            if (method_exists($this, 'convertToNativeUnit')) {
                /** @var float $calculatedNativeValue */
                $calculatedNativeValue = $this->convertToNativeUnit($this->originalValue, $unitName);
            } else {
                $calculatedNativeValue = $this->originalValue * $foundUnit->conversionFactor;
            }
        } catch (\InvalidArgumentException $e) {
            // If not found, check if this class has SI unit support
            if (method_exists($this, 'convertSIUnit')) {
                // Try to convert using SI units
                try {
                    /** @var float $convertedValue */
                    $convertedValue = $this->convertSIUnit($this->originalValue, $unitName, $this->nativeUnit->name);
                    $calculatedNativeValue = $convertedValue;
                    // Create a temporary unit for the original unit
                    $foundUnit = new UnitOfMeasurement($unitName, 1.0);
                } catch (\InvalidArgumentException $siException) {
                    throw $e;
                }
            } else {
                throw $e;
            }
        }

        // Assign readonly properties once
        $this->originalUnit = $foundUnit;
        $this->nativeValue = $calculatedNativeValue;
    }

    abstract protected function initialise(): void;

    protected function addUnit(UnitOfMeasurement $unit): void
    {
        $this->units[] = $unit;
    }

    public function toUnit(string|UnitOfMeasurement $unit): float
    {
        if (is_string($unit)) {
            // Try to find the unit in the registered units first
            try {
                $targetUnit = $this->findUnit($unit);

                return $this->nativeValue / $targetUnit->conversionFactor;
            } catch (\InvalidArgumentException $e) {
                // If not found, check if this class has SI unit support
                if (method_exists($this, 'convertSIUnit')) {
                    // Try to convert using SI units
                    try {
                        /** @var float $result */
                        $result = $this->convertSIUnit($this->nativeValue, $this->nativeUnit->name, $unit);

                        return $result;
                    } catch (\InvalidArgumentException $siException) {
                        // Re-throw the original exception
                        throw $e;
                    }
                }
                throw $e;
            }
        } else {
            return $this->nativeValue / $unit->conversionFactor;
        }
    }

    public function toNativeUnit(): PhysicalQuantity
    {
        return new static($this->nativeValue, $this->nativeUnit);
    }

    public function __toString(): string
    {
        return $this->originalValue.' '.$this->originalUnit->name;
    }

    public function add(PhysicalQuantity $quantity): PhysicalQuantity
    {
        $result = $this->nativeValue + $quantity->nativeValue;

        return new static($result, $this->nativeUnit);
    }

    public function subtract(PhysicalQuantity $quantity): PhysicalQuantity
    {
        $result = $this->nativeValue - $quantity->nativeValue;

        return new static($result, $this->nativeUnit);
    }

    /**
     * Multiply this quantity by another physical quantity.
     * Returns a new derived physical quantity.
     */
    public function multiply(PhysicalQuantity $quantity): PhysicalQuantity
    {
        $result = $this->nativeValue * $quantity->nativeValue;
        $derivedUnitName = $this->nativeUnit->name.' '.$quantity->nativeUnit->name;

        // Handle special cases for derived units
        return match (true) {
            // Length × Length = Area
            $this instanceof Length && $quantity instanceof Length => new Area($result, 'm²'),
            // Area × Length = Volume
            $this instanceof Area && $quantity instanceof Length => new Volume($result, 'm³'),
            // Mass × Acceleration = Force (1 N = 1 kg·m/s² = 1000 g·m/s²)
            // Mass native unit is grams, so we need to convert to kg
            $this instanceof Mass && $quantity instanceof Acceleration => new Force($result / 1000, 'N'),
            // Force × Length = Energy
            $this instanceof Force && $quantity instanceof Length => new Energy($result, 'J'),
            default => throw new \InvalidArgumentException("Cannot multiply {$this->nativeUnit->name} by {$quantity->nativeUnit->name}: resulting unit '{$derivedUnitName}' is not supported"),
        };
    }

    /**
     * Divide this quantity by another physical quantity.
     * Returns a new derived physical quantity.
     */
    public function divide(PhysicalQuantity $quantity): PhysicalQuantity
    {
        if ($quantity->nativeValue === 0.0) {
            throw new \InvalidArgumentException('Cannot divide by zero');
        }

        $result = $this->nativeValue / $quantity->nativeValue;
        $derivedUnitName = $this->nativeUnit->name.'/'.$quantity->nativeUnit->name;

        // Handle special cases for derived units
        return match (true) {
            // Length / Time = Velocity
            $this instanceof Length && $quantity instanceof Time => new Velocity($result, 'm/s'),
            // Length / Velocity = Time
            $this instanceof Length && $quantity instanceof Velocity => new Time($result, 's'),
            // Velocity / Time = Acceleration
            $this instanceof Velocity && $quantity instanceof Time => new Acceleration($result, 'm/s²'),
            // Energy / Time = Power
            $this instanceof Energy && $quantity instanceof Time => new Power($result, 'W'),
            // Force / Area = Pressure
            $this instanceof Force && $quantity instanceof Area => new Pressure($result, 'Pa'),
            // Area / Length = Length
            $this instanceof Area && $quantity instanceof Length => new Length($result, 'm'),
            // Volume / Length = Area
            $this instanceof Volume && $quantity instanceof Length => new Area($result, 'm²'),
            // Volume / Area = Length
            $this instanceof Volume && $quantity instanceof Area => new Length($result, 'm'),
            default => throw new \InvalidArgumentException("Cannot divide {$this->nativeUnit->name} by {$quantity->nativeUnit->name}: resulting unit '{$derivedUnitName}' is not supported"),
        };
    }

    /**
     * Raise this quantity to a power.
     * Returns a new derived physical quantity.
     */
    public function power(int $exponent): PhysicalQuantity
    {
        if ($exponent === 0) {
            throw new \InvalidArgumentException('Cannot raise to power 0');
        }

        if ($exponent === 1) {
            return clone $this;
        }

        $result = $this->nativeValue ** $exponent;

        // Handle special cases for derived units
        return match (true) {
            // Length² = Area
            $this instanceof Length && $exponent === 2 => new Area($result, 'm²'),
            // Length³ = Volume
            $this instanceof Length && $exponent === 3 => new Volume($result, 'm³'),
            // Time² for acceleration calculations
            $this instanceof Time && $exponent === 2 => throw new \InvalidArgumentException('Time² is not a directly supported unit. Use division operations to create derived units like Acceleration (Length/Time²)'),
            default => throw new \InvalidArgumentException("Cannot raise {$this->nativeUnit->name} to power {$exponent}: resulting unit is not supported"),
        };
    }

    protected function convert(UnitOfMeasurement $unit): PhysicalQuantity
    {
        $convertedValue = $this->toUnit($unit);

        return new static($convertedValue, $unit);
    }

    protected function findUnit(string $unitName): UnitOfMeasurement
    {
        $unit = array_find($this->units, fn (UnitOfMeasurement $unit): bool => $unit->isAlias($unitName));

        if ($unit === null) {
            throw new \InvalidArgumentException("Unit '{$unitName}' not found");
        }

        return $unit;
    }
}

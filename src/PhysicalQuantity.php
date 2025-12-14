<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

abstract class PhysicalQuantity implements \JsonSerializable, \Stringable
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

        $this->nativeUnit = $this->units[0];

        $foundUnit = null;
        $calculatedNativeValue = 0.0;

        try {
            $foundUnit = $this->findUnit($unitName);

            if (method_exists($this, 'convertToNativeUnit')) {
                /** @var float $calculatedNativeValue */
                $calculatedNativeValue = $this->convertToNativeUnit($this->originalValue, $unitName);
            } else {
                $calculatedNativeValue = $this->originalValue * $foundUnit->conversionFactor;
            }
        } catch (\InvalidArgumentException $e) {

            if (method_exists($this, 'convertSIUnit')) {

                try {
                    /** @var float $convertedValue */
                    $convertedValue = $this->convertSIUnit($this->originalValue, $unitName, $this->nativeUnit->name);
                    $calculatedNativeValue = $convertedValue;
                    $foundUnit = new UnitOfMeasurement($unitName, 1.0);
                } catch (\InvalidArgumentException) {
                    throw $e;
                }
            } else {
                throw $e;
            }
        }

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

            try {
                $targetUnit = $this->findUnit($unit);

                return $this->nativeValue / $targetUnit->conversionFactor;
            } catch (\InvalidArgumentException $e) {
                if (method_exists($this, 'convertSIUnit')) {
                    try {
                        /** @var float $result */
                        $result = $this->convertSIUnit($this->nativeValue, $this->nativeUnit->name, $unit);

                        return $result;
                    } catch (\InvalidArgumentException) {
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

    /**
     * Parse a string like "5.5 meters" or "100 km" into a PhysicalQuantity.
     *
     * @param  string  $input  The string to parse (e.g., "10.5 m", "5 kilometers")
     * @param  class-string<PhysicalQuantity>|null  $expectedClass  Optional class to enforce type
     *
     * @throws \InvalidArgumentException If parsing fails
     */
    public static function parse(string $input, ?string $expectedClass = null): PhysicalQuantity
    {
        $input = trim($input);

        if (! preg_match('/^([+-]?\d+(?:\.\d+)?)\s*(.*)$/u', $input, $matches)) {
            throw new \InvalidArgumentException("Cannot parse '{$input}': invalid format. Expected format: 'number unit' (e.g., '5.5 meters')");
        }

        $value = (float) $matches[1];
        $unit = trim($matches[2]);

        if ($unit === '') {
            throw new \InvalidArgumentException("Cannot parse '{$input}': no unit specified");
        }

        if ($expectedClass !== null) {
            return new $expectedClass($value, $unit);
        }

        $quantityClasses = [
            Length::class,
            Mass::class,
            Time::class,
            Temperature::class,
            Area::class,
            Volume::class,
            Force::class,
            Energy::class,
            Power::class,
            Pressure::class,
            Velocity::class,
            Acceleration::class,
            Current::class,
            Voltage::class,
            Resistance::class,
            LuminousIntensity::class,
            AmountOfSubstance::class,
            Angle::class,
            Frequency::class,
            Charge::class,
            Density::class,
            Torque::class,
            Capacitance::class,
            Inductance::class,
            MagneticFlux::class,
        ];

        foreach ($quantityClasses as $class) {
            try {
                return new $class($value, $unit);
            } catch (\InvalidArgumentException) {
                continue;
            }
        }

        throw new \InvalidArgumentException("Cannot parse '{$input}': unit '{$unit}' not recognized in any quantity type");
    }

    /**
     * Serialize to JSON-compatible array.
     * Returns both original and native values for flexibility.
     *
     * @return array{value: float, unit: string, nativeValue: float, nativeUnit: string, class: string}
     */
    public function jsonSerialize(): array
    {
        return [
            'value' => $this->originalValue,
            'unit' => $this->originalUnit->name,
            'nativeValue' => $this->nativeValue,
            'nativeUnit' => $this->nativeUnit->name,
            'class' => static::class,
        ];
    }

    /**
     * Create a PhysicalQuantity from JSON data.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromJson(array $data): PhysicalQuantity
    {
        if (! isset($data['class'], $data['value'], $data['unit'])) {
            throw new \InvalidArgumentException('Invalid JSON data: missing required fields (class, value, unit)');
        }

        $className = $data['class'];
        if (! is_string($className)) {
            throw new \InvalidArgumentException('Invalid JSON data: class must be a string');
        }

        if (! class_exists($className)) {
            throw new \InvalidArgumentException("Invalid JSON data: class '{$className}' does not exist");
        }

        if (! is_subclass_of($className, PhysicalQuantity::class)) {
            throw new \InvalidArgumentException("Invalid JSON data: class '{$className}' is not a PhysicalQuantity");
        }

        return new $className($data['value'], $data['unit']);
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
     *
     * @param  class-string<PhysicalQuantity>|null  $resultType  Optional: specify the resulting quantity type for ambiguous operations (e.g., Force × Length can be Energy or Torque)
     */
    public function multiply(PhysicalQuantity $quantity, ?string $resultType = null): PhysicalQuantity
    {
        $result = $this->nativeValue * $quantity->nativeValue;
        $derivedUnitName = $this->nativeUnit->name.' '.$quantity->nativeUnit->name;

        if ($this instanceof Force && $quantity instanceof Length) {
            if ($resultType === Torque::class) {
                return new Torque($result, 'N⋅m');
            }

            return new Energy($result, 'J');
        }
        if ($this instanceof Length && $quantity instanceof Force) {
            if ($resultType === Torque::class) {
                return new Torque($result, 'N⋅m');
            }

            return new Energy($result, 'J');
        }


        return match (true) {
            $this instanceof Length && $quantity instanceof Length => new Area($result, 'm²'),
            $this instanceof Area && $quantity instanceof Length => new Volume($result, 'm³'),
            $this instanceof Mass && $quantity instanceof Acceleration => new Force($result / 1000, 'N'),
            $this instanceof Current && $quantity instanceof Resistance => new Voltage($result, 'V'),
            $this instanceof Resistance && $quantity instanceof Current => new Voltage($result, 'V'),
            $this instanceof Voltage && $quantity instanceof Time => new MagneticFlux($result, 'Wb'),
            $this instanceof Time && $quantity instanceof Voltage => new MagneticFlux($result, 'Wb'),
            $this instanceof Current && $quantity instanceof Time => new Charge($result, 'C'),
            $this instanceof Time && $quantity instanceof Current => new Charge($result, 'C'),
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

        return match (true) {
            $this instanceof Length && $quantity instanceof Time => new Velocity($result, 'm/s'),
            $this instanceof Length && $quantity instanceof Velocity => new Time($result, 's'),
            $this instanceof Velocity && $quantity instanceof Time => new Acceleration($result, 'm/s²'),
            $this instanceof Energy && $quantity instanceof Time => new Power($result, 'W'),
            $this instanceof Force && $quantity instanceof Area => new Pressure($result, 'Pa'),
            $this instanceof Area && $quantity instanceof Length => new Length($result, 'm'),
            $this instanceof Volume && $quantity instanceof Length => new Area($result, 'm²'),
            $this instanceof Volume && $quantity instanceof Area => new Length($result, 'm'),
            $this instanceof Voltage && $quantity instanceof Current => new Resistance($result, 'Ω'),
            $this instanceof Voltage && $quantity instanceof Resistance => new Current($result, 'A'),
            $this instanceof Charge && $quantity instanceof Voltage => new Capacitance($result, 'F'),
            $this instanceof MagneticFlux && $quantity instanceof Current => new Inductance($result, 'H'),
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

        return match (true) {
            $this instanceof Length && $exponent === 2 => new Area($result, 'm²'),
            $this instanceof Length && $exponent === 3 => new Volume($result, 'm³'),
            $this instanceof Time && $exponent === 2 => throw new \InvalidArgumentException('Time² is not a directly supported unit. Use division operations to create derived units like Acceleration (Length/Time²)'),
            default => throw new \InvalidArgumentException("Cannot raise {$this->nativeUnit->name} to power {$exponent}: resulting unit is not supported"),
        };
    }

    /**
     * Compare this quantity to another quantity of the same type.
     * Returns positive if this > other, negative if this < other, 0 if equal.
     *
     * @return int -1, 0, or 1
     *
     * @throws \InvalidArgumentException If quantities are of incompatible types
     */
    public function compareTo(PhysicalQuantity $quantity): int
    {
        if (static::class !== $quantity::class) {
            throw new \InvalidArgumentException(
                sprintf('Cannot compare %s with %s', static::class, $quantity::class)
            );
        }

        return $this->nativeValue <=> $quantity->nativeValue;
    }

    /**
     * Check if this quantity is greater than another quantity.
     *
     * @throws \InvalidArgumentException If quantities are of incompatible types
     */
    public function greaterThan(PhysicalQuantity $quantity): bool
    {
        return $this->compareTo($quantity) > 0;
    }

    /**
     * Check if this quantity is less than another quantity.
     *
     * @throws \InvalidArgumentException If quantities are of incompatible types
     */
    public function lessThan(PhysicalQuantity $quantity): bool
    {
        return $this->compareTo($quantity) < 0;
    }

    /**
     * Check if this quantity is greater than or equal to another quantity.
     *
     * @throws \InvalidArgumentException If quantities are of incompatible types
     */
    public function greaterThanOrEqualTo(PhysicalQuantity $quantity): bool
    {
        return $this->compareTo($quantity) >= 0;
    }

    /**
     * Check if this quantity is less than or equal to another quantity.
     *
     * @throws \InvalidArgumentException If quantities are of incompatible types
     */
    public function lessThanOrEqualTo(PhysicalQuantity $quantity): bool
    {
        return $this->compareTo($quantity) <= 0;
    }

    /**
     * Check if this quantity equals another quantity (within floating-point precision).
     *
     * @param  float  $epsilon  The precision threshold (default: 1e-10)
     *
     * @throws \InvalidArgumentException If quantities are of incompatible types
     */
    public function equals(PhysicalQuantity $quantity, float $epsilon = 1e-10): bool
    {
        if (static::class !== $quantity::class) {
            throw new \InvalidArgumentException(
                sprintf('Cannot compare %s with %s', static::class, $quantity::class)
            );
        }

        return abs($this->nativeValue - $quantity->nativeValue) < $epsilon;
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

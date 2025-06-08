<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

abstract class PhysicalQuantity implements \Stringable
{
    public float $nativeValue;

    /**
     * Represents the native unit associated with a specific context or measurement.
     *
     * This property is used to define and store the standard or default unit relevant
     * to the context in which it is utilized.
     */
    public UnitOfMeasurement $nativeUnit;

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
    protected UnitOfMeasurement $originalUnit;

    /**
     * Constructs a new instance of the class and initializes the object with the provided value and unit.
     *
     * @param  float  $originalValue  The original value to be initialized.
     * @param  string|UnitOfMeasurement  $unit  The unit associated with the original value.
     * @return void
     */
    final public function __construct(public protected(set) float $originalValue, string|UnitOfMeasurement $unit)
    {
        $this->initialise();

        if ($unit instanceof UnitOfMeasurement) {
            $unit = $unit->name;
        }

        foreach ($this->units as $u) {
            if ($u->name == $unit) {
                $this->originalUnit = $u;
            }
        }
    }

    abstract protected function initialise(): void;

    public function addUnit(UnitOfMeasurement $unit): void
    {
        $this->units[] = $unit;
    }

    public function toUnit(string|UnitOfMeasurement $unit): float
    {
        return 0.0;
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
    }

    public function subtract(PhysicalQuantity $quantity): PhysicalQuantity
    {
    }

    protected function convert(UnitOfMeasurement $unit): PhysicalQuantity
    {
    }
}

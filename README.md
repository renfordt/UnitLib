# UnitLib

> A modern, type-safe PHP library for handling physical quantities and unit conversions

[![PHP Version](https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=flat-square&logo=php)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-blue?style=flat-square)](LICENSE)
[![Code Style](https://img.shields.io/badge/code%20style-PSR--12-green?style=flat-square)](https://www.php-fig.org/psr/psr-12/)

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Supported Quantities](#supported-quantities)
- [Usage Guide](#usage-guide)
  - [Creating Quantities](#creating-quantities)
  - [Converting Units](#converting-units)
  - [Arithmetic Operations](#arithmetic-operations)
  - [Derived Units through Operations](#derived-units-through-operations)
  - [Working with SI Prefixes](#working-with-si-prefixes)
  - [Temperature Conversions](#temperature-conversions)
- [Advanced Features](#advanced-features)
- [API Reference](#api-reference)
- [Development](#development)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

---

## Overview

UnitLib is a comprehensive PHP library designed for developers who need to work with physical quantities and perform accurate unit conversions. Built with modern PHP 8.4+ features, it provides a clean, type-safe API for handling measurements across various domains including length, mass, time, energy, and more.

### Why UnitLib?

- **Type Safety**: Leverages PHP 8.4's property hooks for immutable, type-safe quantity objects
- **Zero Ambiguity**: No confusion about what unit you're working with
- **Extensive Unit Support**: From metric to imperial, nautical to scientific units
- **SI Prefix Support**: Automatic handling of kilo-, milli-, micro-, nano-, and more
- **Chainable API**: Fluent interface for readable, maintainable code
- **Well Tested**: Comprehensive test suite with strict coverage requirements

---

## Features

✨ **Comprehensive Unit Coverage**
- **Basic Quantities**: Length, Mass, Time, Temperature
- **Geometric**: Area, Volume
- **Mechanical**: Force, Pressure, Velocity, Acceleration
- **Energy & Power**: Energy, Power
- **Electrical**: Current, Voltage, Luminous Intensity

📐 **Flexible Conversions**
- Metric (SI) units with automatic prefix handling
- Imperial and US customary units
- Specialized units (nautical miles, knots, etc.)

🔒 **Type-Safe Design**
- Immutable quantity objects
- Strict type declarations throughout
- PHPStan level 9 compliant

🧮 **Advanced Mathematical Operations**
- Addition and subtraction of quantities
- Multiplication and division for derived units
- Power operations (e.g., Length² = Area)
- Automatic unit normalization
- Factory methods for convenient creation

🌡️ **Temperature Support**
- Handles offset-based conversions (Celsius, Fahrenheit)
- Kelvin, Rankine support
- Accurate inter-scale conversions

---

## Requirements

- **PHP**: 8.4 or higher
- **Composer**: For dependency management

---

## Installation

Install via Composer:

```bash
composer require renfordt/unit-lib
```

---

## Quick Start

```php
<?php

use Renfordt\UnitLib\Length;
use Renfordt\UnitLib\Mass;
use Renfordt\UnitLib\Temperature;
use Renfordt\UnitLib\Velocity;
use Renfordt\UnitLib\Acceleration;

// Create a length measurement
$distance = new Length(100, 'km');

// Convert to different units
echo $distance->toUnit('mi');  // 62.137119 (miles)
echo $distance->toUnit('m');   // 100000 (meters)

// Use SI prefixes automatically
$height = new Length(1.75, 'm');
echo $height->toUnit('cm');    // 175 (centimeters)

// Perform arithmetic operations
$total = (new Length(5, 'km'))->add(new Length(500, 'm'));
echo $total->toUnit('km');     // 5.5

// Derived units through operations
$area = (new Length(5, 'm'))->multiply(new Length(3, 'm'));
echo $area->toUnit('m²');      // 15 (Area)

$velocity = (new Length(100, 'm'))->divide(new Time(10, 's'));
echo $velocity->toUnit('m/s'); // 10 (Velocity)

// Use factory methods for convenience
$speed = Velocity::fromLengthAndTime(new Length(100, 'm'), new Time(10, 's'));
echo $speed->toUnit('km/h');   // 36

// Work with different quantity types
$weight = new Mass(75, 'kg');
echo $weight->toUnit('lb');    // 165.34675 (pounds)

// Handle temperature conversions
$temp = new Temperature(0, '°C');
echo $temp->toUnit('°F');      // 32
echo $temp->toUnit('K');       // 273.15
```

---

## Supported Quantities

### 📏 Length
- **Metric**: meter (m), kilometer (km), centimeter (cm), millimeter (mm), micrometer (μm), nanometer (nm)
- **Imperial**: inch (in), foot (ft), yard (yd), mile (mi)
- **Nautical**: nautical mile (nmi)

### ⚖️ Mass
- **Metric**: gram (g), kilogram (kg), milligram (mg), tonne (t)
- **Imperial**: ounce (oz), pound (lb), stone (st), ton (US/UK)

### ⏱️ Time
- **SI**: second (s), millisecond (ms), microsecond (μs), nanosecond (ns)
- **Common**: minute (min), hour (h/hr), day, week, year

### 🌡️ Temperature
- **Scales**: Kelvin (K), Celsius (°C), Fahrenheit (°F), Rankine (°R)

### 📐 Area
- **Metric**: square meter (m²), square kilometer (km²), hectare (ha), are (a)
- **Imperial**: square foot (ft²), square yard (yd²), square mile (mi²), acre

### 🧊 Volume
- **Metric**: cubic meter (m³), liter (L), milliliter (mL)
- **Imperial**: cubic foot (ft³), gallon (gal), quart (qt), pint (pt)

### 💪 Force
- **SI**: Newton (N), kilonewton (kN)
- **Other**: pound-force (lbf), dyne

### ⚡ Energy
- **SI**: Joule (J), kilojoule (kJ), megajoule (MJ)
- **Other**: calorie (cal), kilocalorie (kcal), Watt-hour (Wh), kilowatt-hour (kWh)

### 🔌 Power
- **SI**: Watt (W), kilowatt (kW), megawatt (MW)
- **Other**: horsepower (hp)

### 🌊 Pressure
- **SI**: Pascal (Pa), kilopascal (kPa), megapascal (MPa)
- **Other**: bar, atmosphere (atm), psi, torr, mmHg

### 🏃 Velocity
- **Metric**: meter per second (m/s), kilometer per hour (km/h)
- **Imperial**: miles per hour (mph), feet per second (ft/s)
- **Nautical**: knots (kt)

### 🚀 Acceleration
- **SI**: meter per second squared (m/s²)
- **Standard**: standard gravity (g)
- **Imperial**: feet per second squared (ft/s²)
- **Scientific**: galileo (Gal)

### ⚡ Electric Current
- **SI**: ampere (A) with automatic SI prefix support (mA, μA, kA, etc.)

### 💡 Luminous Intensity
- **SI**: candela (cd) with automatic SI prefix support (mcd, kcd, etc.)

### 🔌 Voltage
- **SI**: volt (V) with automatic SI prefix support (mV, μV, kV, MV, etc.)

---

## Usage Guide

### Creating Quantities

All physical quantities are created using their respective class constructors:

```php
use Renfordt\UnitLib\Length;

// Using string unit identifiers
$distance1 = new Length(100, 'm');
$distance2 = new Length(5.5, 'km');
$distance3 = new Length(12, 'ft');

// Using unit aliases
$distance4 = new Length(1, 'meter');
$distance5 = new Length(1, 'metre');  // British spelling
$distance6 = new Length(10, 'inches');
```

### Converting Units

Convert to any supported unit using the `toUnit()` method:

```php
$length = new Length(1500, 'm');

echo $length->toUnit('km');   // 1.5
echo $length->toUnit('mi');   // 0.932057
echo $length->toUnit('ft');   // 4921.26
```

### Arithmetic Operations

Quantities can be added or subtracted, even if they're in different units:

```php
$distance1 = new Length(1, 'km');
$distance2 = new Length(500, 'm');

// Addition
$total = $distance1->add($distance2);
echo $total->toUnit('km');  // 1.5
echo $total->toUnit('m');   // 1500

// Subtraction
$difference = $distance1->subtract($distance2);
echo $difference->toUnit('m');  // 500
```

**Important**: The result is always in the native unit (the first unit defined in the class). Use `toUnit()` to convert to your desired unit.

### Derived Units through Operations

The library automatically creates derived quantity types through multiplication, division, and power operations:

```php
use Renfordt\UnitLib\{Length, Time, Mass, Force, Area, Volume, Velocity, Acceleration, Energy, Power, Pressure};

// Length × Length = Area
$length1 = new Length(5, 'm');
$length2 = new Length(3, 'm');
$area = $length1->multiply($length2);
echo $area->toUnit('m²');  // 15

// Area × Length = Volume
$volume = $area->multiply(new Length(2, 'm'));
echo $volume->toUnit('m³');  // 30

// Length² = Area (using power)
$area = (new Length(5, 'm'))->power(2);
echo $area->toUnit('m²');  // 25

// Length³ = Volume (using power)
$volume = (new Length(3, 'm'))->power(3);
echo $volume->toUnit('m³');  // 27

// Length / Time = Velocity
$distance = new Length(100, 'm');
$time = new Time(10, 's');
$velocity = $distance->divide($time);
echo $velocity->toUnit('m/s');  // 10
echo $velocity->toUnit('km/h');  // 36

// Velocity / Time = Acceleration
$acceleration = $velocity->divide($time);
echo $acceleration->toUnit('m/s²');  // 1

// Mass × Acceleration = Force
$mass = new Mass(10, 'kg');
$accel = new Acceleration(9.8, 'm/s²');
$force = $mass->multiply($accel);
echo $force->toUnit('N');  // 98

// Force × Length = Energy
$work = $force->multiply(new Length(5, 'm'));
echo $work->toUnit('J');  // 490

// Energy / Time = Power
$power = $work->divide($time);
echo $power->toUnit('W');  // 49

// Force / Area = Pressure
$pressure = $force->divide($area);
echo $pressure->toUnit('Pa');  // Pressure in Pascals

// Reverse operations: Volume / Length = Area
$derivedArea = $volume->divide(new Length(2, 'm'));
echo $derivedArea->toUnit('m²');  // Returns area
```

#### Factory Methods for Convenience

Some derived quantity classes provide static factory methods for easier creation:

```php
// Velocity from Length and Time
$velocity = Velocity::fromLengthAndTime(
    new Length(100, 'm'),
    new Time(10, 's')
);
echo $velocity->toUnit('km/h');  // 36

// Acceleration from Velocity and Time
$acceleration = Acceleration::fromVelocityAndTime(
    new Velocity(50, 'm/s'),
    new Time(5, 's')
);
echo $acceleration->toUnit('m/s²');  // 10

// Acceleration from Length and Time squared
$acceleration = Acceleration::fromLengthAndTime(
    new Length(100, 'm'),
    new Time(10, 's')
);
echo $acceleration->toUnit('m/s²');  // 1
```

### Working with SI Prefixes

For quantities that support SI units (Length, Mass, Time, etc.), you can use metric prefixes automatically:

```php
$length = new Length(1, 'm');

// Convert to any SI prefix
echo $length->toUnit('km');   // 0.001 (kilometer)
echo $length->toUnit('cm');   // 100 (centimeter)
echo $length->toUnit('mm');   // 1000 (millimeter)
echo $length->toUnit('μm');   // 1000000 (micrometer)
echo $length->toUnit('nm');   // 1000000000 (nanometer)

// Works in reverse too
$tiny = new Length(500, 'μm');
echo $tiny->toUnit('mm');     // 0.5
echo $tiny->toUnit('m');      // 0.0005
```

**Supported SI Prefixes**:
- Quetta (Q): 10³⁰
- Ronna (R): 10²⁷
- Yotta (Y): 10²⁴
- Zetta (Z): 10²¹
- Exa (E): 10¹⁸
- Peta (P): 10¹⁵
- Tera (T): 10¹²
- Giga (G): 10⁹
- Mega (M): 10⁶
- Kilo (k): 10³
- Hecto (h): 10²
- Deca (da): 10¹
- Deci (d): 10⁻¹
- Centi (c): 10⁻²
- Milli (m): 10⁻³
- Micro (μ/u): 10⁻⁶
- Nano (n): 10⁻⁹
- Pico (p): 10⁻¹²
- Femto (f): 10⁻¹⁵
- Atto (a): 10⁻¹⁸
- Zepto (z): 10⁻²¹
- Yocto (y): 10⁻²⁴
- Ronto (r): 10⁻²⁷
- Quecto (q): 10⁻³⁰

### Temperature Conversions

Temperature requires special handling due to offset-based scales:

```php
use Renfordt\UnitLib\Temperature;

// Freezing point of water
$freezing = new Temperature(0, '°C');
echo $freezing->toUnit('°F');  // 32
echo $freezing->toUnit('K');   // 273.15

// Boiling point of water
$boiling = new Temperature(100, '°C');
echo $boiling->toUnit('°F');   // 212
echo $boiling->toUnit('K');    // 373.15

// Absolute zero
$absolute = new Temperature(0, 'K');
echo $absolute->toUnit('°C');  // -273.15
echo $absolute->toUnit('°F');  // -459.67

// Using aliases
$temp = new Temperature(72, 'F');        // Fahrenheit
echo $temp->toUnit('celsius');           // ~22.22
```

---

## Advanced Features

### Accessing Original Values

Each quantity preserves both the original value/unit and the native (normalized) value:

```php
$length = new Length(100, 'cm');

echo $length->originalValue;        // 100
echo $length->originalUnit->name;   // "cm"
echo $length->nativeValue;          // 1 (converted to meters)
echo $length->nativeUnit->name;     // "m"
```

### String Representation

Quantities implement `Stringable` for easy display:

```php
$weight = new Mass(75, 'kg');
echo $weight;  // "75 kg"

$distance = new Length(5280, 'ft');
echo $distance;  // "5280 ft"
```

### Converting to Native Unit

Get a new quantity instance in the native unit:

```php
$length = new Length(100, 'cm');
$native = $length->toNativeUnit();

echo $native->originalValue;  // 1
echo $native->originalUnit->name;  // "m"
```

### Unit Aliases

Most units support multiple aliases for flexibility:

```php
// Length aliases
new Length(1, 'm');
new Length(1, 'meter');
new Length(1, 'metre');
new Length(1, 'meters');
new Length(1, 'metres');

// Mass aliases
new Mass(1, 'kg');
new Mass(1, 'kilogram');
new Mass(1, 'kilograms');

// Time aliases
new Time(1, 's');
new Time(1, 'sec');
new Time(1, 'second');
new Time(1, 'seconds');
```

---

## API Reference

### PhysicalQuantity (Base Class)

All quantity classes extend this abstract base.

#### Properties

```php
public readonly float $originalValue;     // The value as provided in constructor
public readonly UnitOfMeasurement $originalUnit;  // The unit as provided
public readonly float $nativeValue;       // Value converted to native unit
public readonly UnitOfMeasurement $nativeUnit;    // The native (base) unit
```

#### Methods

```php
// Convert to a different unit
public function toUnit(string|UnitOfMeasurement $unit): float

// Convert to native unit
public function toNativeUnit(): PhysicalQuantity

// Add two quantities (returns new instance in native unit)
public function add(PhysicalQuantity $quantity): PhysicalQuantity

// Subtract quantities (returns new instance in native unit)
public function subtract(PhysicalQuantity $quantity): PhysicalQuantity

// Multiply two quantities (returns derived quantity type)
// Examples: Length × Length = Area, Mass × Acceleration = Force
public function multiply(PhysicalQuantity $quantity): PhysicalQuantity

// Divide two quantities (returns derived quantity type)
// Examples: Length / Time = Velocity, Energy / Time = Power
public function divide(PhysicalQuantity $quantity): PhysicalQuantity

// Raise quantity to an integer power (returns derived quantity type)
// Examples: Length² = Area, Length³ = Volume
public function power(int $exponent): PhysicalQuantity

// String representation: "value unit"
public function __toString(): string
```

### UnitOfMeasurement

Represents a unit of measurement with conversion factors.

```php
// Constructor
public function __construct(string $name, float $conversionFactor)

// Add alternative names for the unit
public function addAlias(string $alias): void

// Check if a string matches this unit or its aliases
public function isAlias(string $unitName): bool

// Get the conversion factor to native unit
public function getConversionFactor(): float
```

---

## Development

### Project Structure

```
src/
├── PhysicalQuantity.php      # Abstract base class
├── UnitOfMeasurement.php     # Unit representation
├── HasSIUnits.php            # Trait for SI prefix support
├── Length.php                # Length quantities
├── Mass.php                  # Mass quantities
├── Time.php                  # Time quantities
├── Temperature.php           # Temperature quantities
├── Area.php                  # Area quantities
├── Volume.php                # Volume quantities
├── Force.php                 # Force quantities
├── Energy.php                # Energy quantities
├── Power.php                 # Power quantities
├── Pressure.php              # Pressure quantities
├── Velocity.php              # Velocity quantities (with factory methods)
├── Acceleration.php          # Acceleration quantities (with factory methods)
├── Current.php               # Electric current quantities
├── Voltage.php               # Voltage quantities
└── LuminousIntensity.php     # Luminous intensity quantities

tests/
├── *Test.php                 # Corresponding test files
├── ImmutabilityTest.php      # Tests for immutability
└── DerivedUnitsTest.php      # Tests for derived unit operations
```

### Code Quality Tools

The project uses several tools to maintain code quality:

- **Laravel Pint**: PSR-12 code style with strict types
- **PHPStan**: Static analysis at high strictness level
- **Rector**: Automated refactoring and code quality improvements
- **PHPUnit**: Comprehensive test coverage

---

## Testing

### Running Tests

```bash
# Run all tests (refactoring checks, linting, type analysis, unit tests)
composer test

# Run only unit tests
composer test:unit

# Run static analysis
composer test:types

# Check code style
composer test:lint

# Check refactoring opportunities
composer test:refacto
```

### Running Specific Tests

```bash
# Run a specific test method
vendor/bin/phpunit --filter test_converts_meters_to_feet

# Run a specific test file
vendor/bin/phpunit tests/LengthTest.php
```

### Code Coverage

When xdebug is enabled, PHPUnit generates HTML coverage reports:

```bash
composer test:unit
# View coverage at: .phpunit.cache/code-coverage/index.html
```

### Auto-fixing Issues

```bash
# Fix code style automatically
composer lint

# Apply automated refactorings
composer refacto
```

---

## Contributing

Contributions are welcome! Here's how you can help:

1. **Fork the repository**
2. **Create a feature branch**: `git checkout -b feature/amazing-feature`
3. **Make your changes** and ensure tests pass: `composer test`
4. **Fix code style**: `composer lint`
5. **Commit your changes**: `git commit -m 'Add amazing feature'`
6. **Push to the branch**: `git push origin feature/amazing-feature`
7. **Open a Pull Request**

### Adding New Physical Quantities

To add a new quantity type:

1. Create a new class extending `PhysicalQuantity`
2. Implement the `initialise()` method to define units
3. Use `HasSIUnits` trait if SI prefixes apply
4. Override `getSIBaseUnit()` to specify the base unit symbol
5. Set native unit conversion factor to 1.0
6. Add aliases for common unit names
7. Add factory methods if the quantity can be derived from others
8. Create comprehensive tests with `#[CoversClass]` attribute

Example:

```php
<?php

namespace Renfordt\UnitLib;

class Density extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'kg/m³';  // Base unit for SI prefixes
    }

    /**
     * Create Density from Mass and Volume.
     */
    public static function fromMassAndVolume(Mass $mass, Volume $volume): self
    {
        /** @var self */
        return $mass->divide($volume);
    }

    protected function initialise(): void
    {
        // Native unit: kilograms per cubic meter
        $kgPerM3 = new UnitOfMeasurement('kg/m³', 1);
        $kgPerM3->addAlias('kg/m3');
        $kgPerM3->addAlias('kilogram per cubic meter');
        $this->addUnit($kgPerM3);

        // Grams per cubic centimeter
        $gPerCm3 = new UnitOfMeasurement('g/cm³', 1000);
        $gPerCm3->addAlias('g/cm3');
        $this->addUnit($gPerCm3);

        // Pounds per cubic foot
        $lbPerFt3 = new UnitOfMeasurement('lb/ft³', 16.0185);
        $lbPerFt3->addAlias('lb/ft3');
        $this->addUnit($lbPerFt3);
    }
}
```

---

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

## Acknowledgments

- Built with modern PHP 8.4 features
- Original concept inspired by [PhpUnitsOfMeasure/php-units-of-measure](https://github.com/PhpUnitsOfMeasure/php-units-of-measure)
- Redesigned with property hooks, immutability, and derived unit operations
- Conversion factors sourced from NIST and other scientific standards

---

**Made with ❤️ for the PHP community**

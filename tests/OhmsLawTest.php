<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Current;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\Resistance;
use Renfordt\UnitLib\UnitOfMeasurement;
use Renfordt\UnitLib\Voltage;

#[CoversClass(Voltage::class)]
#[CoversClass(Current::class)]
#[CoversClass(Resistance::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
class OhmsLawTest extends TestCase
{
    // V = I × R tests

    public function test_voltage_from_current_times_resistance(): void
    {
        $current = new Current(2, 'A');
        $resistance = new Resistance(100, 'Ω');
        $voltage = $current->multiply($resistance);

        self::assertInstanceOf(Voltage::class, $voltage);
        self::assertSame(200.0, $voltage->toUnit('V'));
    }

    public function test_voltage_from_resistance_times_current(): void
    {
        $resistance = new Resistance(50, 'Ω');
        $current = new Current(0.5, 'A');
        $voltage = $resistance->multiply($current);

        self::assertInstanceOf(Voltage::class, $voltage);
        self::assertSame(25.0, $voltage->toUnit('V'));
    }

    public function test_voltage_from_factory_method(): void
    {
        $current = new Current(3, 'A');
        $resistance = new Resistance(10, 'Ω');
        $voltage = Voltage::fromCurrentAndResistance($current, $resistance);

        self::assertInstanceOf(Voltage::class, $voltage);
        self::assertSame(30.0, $voltage->toUnit('V'));
    }

    public function test_voltage_calculation_with_milliamps(): void
    {
        $current = new Current(500, 'mA');
        $resistance = new Resistance(100, 'Ω');
        $voltage = $current->multiply($resistance);

        self::assertSame(50.0, $voltage->toUnit('V'));
    }

    public function test_voltage_calculation_with_kilohms(): void
    {
        $current = new Current(1, 'A');
        $resistance = new Resistance(2, 'kΩ');
        $voltage = $current->multiply($resistance);

        self::assertSame(2000.0, $voltage->toUnit('V'));
    }

    // I = V / R tests

    public function test_current_from_voltage_divided_by_resistance(): void
    {
        $voltage = new Voltage(12, 'V');
        $resistance = new Resistance(4, 'Ω');
        $current = $voltage->divide($resistance);

        self::assertInstanceOf(Current::class, $current);
        self::assertSame(3.0, $current->toUnit('A'));
    }

    public function test_current_from_factory_method(): void
    {
        $voltage = new Voltage(230, 'V');
        $resistance = new Resistance(46, 'Ω');
        $current = Current::fromVoltageAndResistance($voltage, $resistance);

        self::assertInstanceOf(Current::class, $current);
        self::assertSame(5.0, $current->toUnit('A'));
    }

    public function test_current_calculation_with_millivolts(): void
    {
        $voltage = new Voltage(500, 'mV');
        $resistance = new Resistance(100, 'Ω');
        $current = $voltage->divide($resistance);

        self::assertSame(0.005, $current->toUnit('A'));
        self::assertSame(5.0, $current->toUnit('mA'));
    }

    public function test_current_calculation_with_megohms(): void
    {
        $voltage = new Voltage(100, 'V');
        $resistance = new Resistance(1, 'MΩ');
        $current = $voltage->divide($resistance);

        self::assertEqualsWithDelta(0.0001, $current->toUnit('A'), 0.0000001);
        self::assertEqualsWithDelta(100, $current->toUnit('μA'), 0.001);
    }

    // R = V / I tests

    public function test_resistance_from_voltage_divided_by_current(): void
    {
        $voltage = new Voltage(12, 'V');
        $current = new Current(0.5, 'A');
        $resistance = $voltage->divide($current);

        self::assertInstanceOf(Resistance::class, $resistance);
        self::assertSame(24.0, $resistance->toUnit('Ω'));
    }

    public function test_resistance_from_factory_method(): void
    {
        $voltage = new Voltage(9, 'V');
        $current = new Current(0.003, 'A');
        $resistance = Resistance::fromVoltageAndCurrent($voltage, $current);

        self::assertInstanceOf(Resistance::class, $resistance);
        self::assertSame(3000.0, $resistance->toUnit('Ω'));
        self::assertSame(3.0, $resistance->toUnit('kΩ'));
    }

    public function test_resistance_calculation_with_milliamps(): void
    {
        $voltage = new Voltage(5, 'V');
        $current = new Current(10, 'mA');
        $resistance = $voltage->divide($current);

        self::assertSame(500.0, $resistance->toUnit('Ω'));
    }

    public function test_resistance_calculation_with_kilovolts(): void
    {
        $voltage = new Voltage(10, 'kV');
        $current = new Current(1, 'A');
        $resistance = $voltage->divide($current);

        self::assertSame(10_000.0, $resistance->toUnit('Ω'));
        self::assertSame(10.0, $resistance->toUnit('kΩ'));
    }

    // Real-world examples

    public function test_led_circuit_example(): void
    {
        // LED circuit: 5V supply, 330Ω resistor
        // Expected current: I = V/R = 5/330 ≈ 0.0152A = 15.2mA
        $voltage = new Voltage(5, 'V');
        $resistance = new Resistance(330, 'Ω');
        $current = $voltage->divide($resistance);

        self::assertEqualsWithDelta(15.15, $current->toUnit('mA'), 0.01);
    }

    public function test_household_circuit_example(): void
    {
        // Household circuit: 230V, 10A
        // Expected resistance: R = V/I = 230/10 = 23Ω
        $voltage = new Voltage(230, 'V');
        $current = new Current(10, 'A');
        $resistance = $voltage->divide($current);

        self::assertSame(23.0, $resistance->toUnit('Ω'));
    }

    public function test_battery_example(): void
    {
        // AA battery: 1.5V, 0.1A through 15Ω load
        // Verify: V = I × R = 0.1 × 15 = 1.5V
        $current = new Current(0.1, 'A');
        $resistance = new Resistance(15, 'Ω');
        $voltage = $current->multiply($resistance);

        self::assertSame(1.5, $voltage->toUnit('V'));
    }

    public function test_resistor_divider_circuit(): void
    {
        // Simple resistor network: 12V across two 6Ω resistors in series
        // Total resistance: 12Ω, Current: I = 12V/12Ω = 1A
        $voltage = new Voltage(12, 'V');
        $r1 = new Resistance(6, 'Ω');
        $r2 = new Resistance(6, 'Ω');
        $totalResistance = $r1->add($r2);
        $current = $voltage->divide($totalResistance);

        self::assertSame(1.0, $current->toUnit('A'));
    }

    public function test_power_calculation_setup(): void
    {
        // 100W bulb at 230V: I = P/V, but we can verify R = V²/P
        // Expected: R = 230²/100 = 529Ω, I = 230/529 ≈ 0.435A
        $voltage = new Voltage(230, 'V');
        $resistance = new Resistance(529, 'Ω');
        $current = $voltage->divide($resistance);

        self::assertEqualsWithDelta(0.435, $current->toUnit('A'), 0.001);
    }

    public function test_ohms_law_round_trip(): void
    {
        // Start with V and R, calculate I, then verify we can get back V and R
        $originalVoltage = new Voltage(24, 'V');
        $originalResistance = new Resistance(8, 'Ω');

        // Calculate current
        $current = $originalVoltage->divide($originalResistance);
        self::assertSame(3.0, $current->toUnit('A'));

        // Calculate voltage from current and resistance
        $calculatedVoltage = $current->multiply($originalResistance);
        self::assertSame(24.0, $calculatedVoltage->toUnit('V'));

        // Calculate resistance from voltage and current
        $calculatedResistance = $originalVoltage->divide($current);
        self::assertSame(8.0, $calculatedResistance->toUnit('Ω'));
    }

    public function test_microcontroller_circuit(): void
    {
        // 3.3V microcontroller pin with 10kΩ pull-up resistor
        // Expected current: I = 3.3V / 10kΩ = 0.33mA
        $voltage = new Voltage(3.3, 'V');
        $resistance = new Resistance(10, 'kΩ');
        $current = $voltage->divide($resistance);

        self::assertEqualsWithDelta(0.33, $current->toUnit('mA'), 0.01);
    }
}

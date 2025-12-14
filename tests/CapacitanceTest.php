<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Capacitance;
use Renfordt\UnitLib\Charge;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\UnitOfMeasurement;
use Renfordt\UnitLib\Voltage;

#[CoversClass(Capacitance::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
#[CoversClass(Charge::class)]
#[CoversClass(Voltage::class)]
final class CapacitanceTest extends TestCase
{
    public function test_creates_capacitance_with_farads(): void
    {
        $capacitance = new Capacitance(100, 'F');

        $this->assertEquals(100, $capacitance->originalValue);
    }

    public function test_converts_farads_to_microfarads(): void
    {
        $capacitance = new Capacitance(0.001, 'F');

        $this->assertEqualsWithDelta(1000, $capacitance->toUnit('μF'), 0.00001);
    }

    public function test_converts_microfarads_to_farads(): void
    {
        $capacitance = new Capacitance(1000, 'μF');

        $this->assertEqualsWithDelta(0.001, $capacitance->toUnit('F'), 0.000001);
    }

    public function test_factory_from_charge_and_voltage(): void
    {
        $charge = new Charge(10, 'C');
        $voltage = new Voltage(5, 'V');

        $capacitance = Capacitance::fromChargeAndVoltage($charge, $voltage);

        $this->assertInstanceOf(Capacitance::class, $capacitance);
        $this->assertEquals(2, $capacitance->toUnit('F'));
    }

    public function test_throws_exception_for_zero_voltage(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('zero voltage');

        $charge = new Charge(10, 'C');
        $voltage = new Voltage(0, 'V');

        Capacitance::fromChargeAndVoltage($charge, $voltage);
    }

    public function test_charge_divided_by_voltage_creates_capacitance(): void
    {
        $charge = new Charge(20, 'C');
        $voltage = new Voltage(10, 'V');

        $capacitance = $charge->divide($voltage);

        $this->assertInstanceOf(Capacitance::class, $capacitance);
        $this->assertEquals(2, $capacitance->toUnit('F'));
    }

    public function test_si_prefix_conversions(): void
    {
        $capacitance = new Capacitance(1, 'mF');

        $this->assertEquals(0.001, $capacitance->toUnit('F'));
        $this->assertEquals(1000, $capacitance->toUnit('μF'));
    }

    public function test_uses_unit_aliases(): void
    {
        $capacitance1 = new Capacitance(100, 'F');
        $capacitance2 = new Capacitance(100, 'farad');
        $capacitance3 = new Capacitance(100, 'farads');

        $this->assertEquals($capacitance1->nativeValue, $capacitance2->nativeValue);
        $this->assertEquals($capacitance1->nativeValue, $capacitance3->nativeValue);
    }

    public function test_native_value_is_in_farads(): void
    {
        $capacitance = new Capacitance(1000, 'μF');

        $this->assertEqualsWithDelta(0.001, $capacitance->nativeValue, 0.000001);
        $this->assertEquals('F', $capacitance->nativeUnit->name);
    }

    public function test_to_string_representation(): void
    {
        $capacitance = new Capacitance(100, 'μF');

        $this->assertEquals('100 μF', (string) $capacitance);
    }

    public function test_throws_exception_for_invalid_unit(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Capacitance(100, 'invalid_unit');
    }
}

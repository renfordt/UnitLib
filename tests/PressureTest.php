<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Pressure;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Pressure::class)]
#[CoversClass(UnitOfMeasurement::class)]
class PressureTest extends TestCase
{
    public function test_creates_pressure_with_pascals(): void
    {
        $pressure = new Pressure(1000, 'Pa');
        $this->assertEquals(1000, $pressure->originalValue);
    }

    public function test_converts_pascals_to_kilopascals(): void
    {
        $pressure = new Pressure(1000, 'Pa');
        $this->assertEquals(1, $pressure->toUnit('kPa'));
    }

    public function test_converts_kilopascals_to_pascals(): void
    {
        $pressure = new Pressure(1, 'kPa');
        $this->assertEquals(1000, $pressure->toUnit('Pa'));
    }

    public function test_converts_bar_to_pascals(): void
    {
        $pressure = new Pressure(1, 'bar');
        $this->assertEquals(100000, $pressure->toUnit('Pa'));
    }

    public function test_converts_bar_to_kilopascals(): void
    {
        $pressure = new Pressure(1, 'bar');
        $this->assertEquals(100, $pressure->toUnit('kPa'));
    }

    public function test_converts_millibar_to_pascals(): void
    {
        $pressure = new Pressure(1, 'mbar');
        $this->assertEquals(100, $pressure->toUnit('Pa'));
    }

    public function test_converts_atmosphere_to_pascals(): void
    {
        $pressure = new Pressure(1, 'atm');
        $this->assertEquals(101325, $pressure->toUnit('Pa'));
    }

    public function test_converts_atmosphere_to_bar(): void
    {
        $pressure = new Pressure(1, 'atm');
        $this->assertEqualsWithDelta(1.01325, $pressure->toUnit('bar'), 0.00001);
    }

    public function test_converts_torr_to_pascals(): void
    {
        $pressure = new Pressure(1, 'Torr');
        $this->assertEqualsWithDelta(133.322368421, $pressure->toUnit('Pa'), 0.000000001);
    }

    public function test_converts_mmhg_to_pascals(): void
    {
        $pressure = new Pressure(760, 'mmHg');
        $this->assertEqualsWithDelta(101325, $pressure->toUnit('Pa'), 0.1);
    }

    public function test_converts_psi_to_pascals(): void
    {
        $pressure = new Pressure(1, 'psi');
        $this->assertEqualsWithDelta(6894.757293168, $pressure->toUnit('Pa'), 0.000000001);
    }

    public function test_converts_psi_to_bar(): void
    {
        $pressure = new Pressure(14.5038, 'psi');
        $this->assertEqualsWithDelta(1, $pressure->toUnit('bar'), 0.0001);
    }

    public function test_converts_pascals_to_psi(): void
    {
        $pressure = new Pressure(100000, 'Pa');
        $this->assertEqualsWithDelta(14.5038, $pressure->toUnit('psi'), 0.0001);
    }

    public function test_converts_psf_to_pascals(): void
    {
        $pressure = new Pressure(1, 'psf');
        $this->assertEqualsWithDelta(47.880258980336, $pressure->toUnit('Pa'), 0.000000000001);
    }

    public function test_converts_inches_mercury_to_pascals(): void
    {
        $pressure = new Pressure(1, 'inHg');
        $this->assertEqualsWithDelta(3386.389, $pressure->toUnit('Pa'), 0.001);
    }

    public function test_converts_technical_atmosphere_to_pascals(): void
    {
        $pressure = new Pressure(1, 'at');
        $this->assertEquals(98066.5, $pressure->toUnit('Pa'));
    }

    public function test_uses_pressure_aliases(): void
    {
        $pressure1 = new Pressure(1, 'Pa');
        $pressure2 = new Pressure(1, 'pascal');
        $pressure3 = new Pressure(1, 'pascals');

        $this->assertEquals(0.001, $pressure1->toUnit('kPa'));
        $this->assertEquals(0.001, $pressure2->toUnit('kPa'));
        $this->assertEquals(0.001, $pressure3->toUnit('kPa'));
    }

    public function test_native_value_is_in_pascals(): void
    {
        $pressure = new Pressure(1, 'bar');
        $this->assertEquals(100000, $pressure->nativeValue);
    }

    public function test_add_pressures_in_different_units(): void
    {
        $pressure1 = new Pressure(1, 'bar');
        $pressure2 = new Pressure(50, 'kPa');
        $result = $pressure1->add($pressure2);

        $this->assertEquals(1.5, $result->toUnit('bar'));
    }

    public function test_subtract_pressures_in_different_units(): void
    {
        $pressure1 = new Pressure(1, 'bar');
        $pressure2 = new Pressure(50, 'kPa');
        $result = $pressure1->subtract($pressure2);

        $this->assertEquals(0.5, $result->toUnit('bar'));
    }
}

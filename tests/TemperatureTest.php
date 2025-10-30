<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\Temperature;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Temperature::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
class TemperatureTest extends TestCase
{
    public function test_creates_temperature_with_celsius(): void
    {
        $temp = new Temperature(25, '°C');
        $this->assertEquals(25, $temp->originalValue);
    }

    public function test_converts_celsius_to_kelvin(): void
    {
        $temp = new Temperature(0, '°C');
        $this->assertEqualsWithDelta(273.15, $temp->toUnit('K'), 0.001);
    }

    public function test_converts_kelvin_to_celsius(): void
    {
        $temp = new Temperature(273.15, 'K');
        $this->assertEqualsWithDelta(0, $temp->toUnit('°C'), 0.001);
    }

    public function test_converts_celsius_to_fahrenheit(): void
    {
        $temp = new Temperature(0, '°C');
        $this->assertEqualsWithDelta(32, $temp->toUnit('°F'), 0.001);
    }

    public function test_converts_fahrenheit_to_celsius(): void
    {
        $temp = new Temperature(32, '°F');
        $this->assertEqualsWithDelta(0, $temp->toUnit('°C'), 0.001);
    }

    public function test_converts_fahrenheit_to_kelvin(): void
    {
        $temp = new Temperature(32, '°F');
        $this->assertEqualsWithDelta(273.15, $temp->toUnit('K'), 0.001);
    }

    public function test_converts_kelvin_to_fahrenheit(): void
    {
        $temp = new Temperature(273.15, 'K');
        $this->assertEqualsWithDelta(32, $temp->toUnit('°F'), 0.001);
    }

    public function test_converts_boiling_point_water(): void
    {
        $temp = new Temperature(100, '°C');
        $this->assertEqualsWithDelta(212, $temp->toUnit('°F'), 0.001);
        $this->assertEqualsWithDelta(373.15, $temp->toUnit('K'), 0.001);
    }

    public function test_converts_rankine_to_kelvin(): void
    {
        $temp = new Temperature(491.67, '°R');
        $this->assertEqualsWithDelta(273.15, $temp->toUnit('K'), 0.001);
    }

    public function test_converts_kelvin_to_rankine(): void
    {
        $temp = new Temperature(273.15, 'K');
        $this->assertEqualsWithDelta(491.67, $temp->toUnit('°R'), 0.001);
    }

    public function test_converts_rankine_to_fahrenheit(): void
    {
        $temp = new Temperature(491.67, '°R');
        $this->assertEqualsWithDelta(32, $temp->toUnit('°F'), 0.001);
    }

    public function test_converts_body_temperature(): void
    {
        $temp = new Temperature(98.6, '°F');
        $this->assertEqualsWithDelta(37, $temp->toUnit('°C'), 0.1);
    }

    public function test_uses_temperature_aliases(): void
    {
        $temp1 = new Temperature(0, '°C');
        $temp2 = new Temperature(0, 'C');
        $temp3 = new Temperature(0, 'celsius');

        $this->assertEqualsWithDelta(273.15, $temp1->toUnit('K'), 0.001);
        $this->assertEqualsWithDelta(273.15, $temp2->toUnit('K'), 0.001);
        $this->assertEqualsWithDelta(273.15, $temp3->toUnit('K'), 0.001);
    }

    public function test_native_value_is_in_kelvin(): void
    {
        $temp = new Temperature(0, '°C');
        $this->assertEqualsWithDelta(273.15, $temp->nativeValue, 0.001);
    }

    public function test_add_temperatures_in_different_units(): void
    {
        $temp1 = new Temperature(273.15, 'K');
        $temp2 = new Temperature(10, '°C');
        $result = $temp1->add($temp2);

        // 273.15K + 283.15K (10°C) = 556.3K
        $this->assertEqualsWithDelta(556.3, $result->toUnit('K'), 0.001);
    }

    public function test_subtract_temperatures_in_different_units(): void
    {
        $temp1 = new Temperature(283.15, 'K');
        $temp2 = new Temperature(273.15, 'K');
        $result = $temp1->subtract($temp2);

        $this->assertEqualsWithDelta(10, $result->toUnit('K'), 0.001);
    }

    public function test_throws_exception_for_invalid_unit_in_constructor(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unit 'invalid' not found");

        // Temperature doesn't use HasSIUnits trait, so this should trigger the else path
        new Temperature(25, 'invalid');
    }

    public function test_throws_exception_for_invalid_unit_in_to_unit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unit 'invalid' not found");

        $temp = new Temperature(25, '°C');
        $temp->toUnit('invalid');
    }
}

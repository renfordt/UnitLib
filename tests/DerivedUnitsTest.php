<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Acceleration;
use Renfordt\UnitLib\Area;
use Renfordt\UnitLib\Energy;
use Renfordt\UnitLib\Force;
use Renfordt\UnitLib\Length;
use Renfordt\UnitLib\Mass;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\Power;
use Renfordt\UnitLib\Pressure;
use Renfordt\UnitLib\Time;
use Renfordt\UnitLib\UnitOfMeasurement;
use Renfordt\UnitLib\Velocity;
use Renfordt\UnitLib\Volume;

#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(Area::class)]
#[CoversClass(Volume::class)]
#[CoversClass(Force::class)]
#[CoversClass(Energy::class)]
#[CoversClass(Power::class)]
#[CoversClass(Pressure::class)]
#[CoversClass(Length::class)]
#[CoversClass(Mass::class)]
#[CoversClass(Time::class)]
#[CoversClass(Velocity::class)]
#[CoversClass(Acceleration::class)]
#[CoversClass(UnitOfMeasurement::class)]
final class DerivedUnitsTest extends TestCase
{
    public function test_length_squared_creates_area(): void
    {
        $length = new Length(5, 'm');
        $area = $length->power(2);

        $this->assertInstanceOf(Area::class, $area);
        $this->assertEqualsWithDelta(25.0, $area->toUnit('m²'), 0.0001);
    }

    public function test_length_cubed_creates_volume(): void
    {
        $length = new Length(3, 'm');
        $volume = $length->power(3);

        $this->assertInstanceOf(Volume::class, $volume);
        $this->assertEqualsWithDelta(27.0, $volume->toUnit('m³'), 0.0001);
    }

    public function test_length_times_length_creates_area(): void
    {
        $width = new Length(4, 'm');
        $height = new Length(5, 'm');
        $area = $width->multiply($height);

        $this->assertInstanceOf(Area::class, $area);
        $this->assertEqualsWithDelta(20.0, $area->toUnit('m²'), 0.0001);
    }

    public function test_area_times_length_creates_volume(): void
    {
        $area = new Area(10, 'm²');
        $height = new Length(3, 'm');
        $volume = $area->multiply($height);

        $this->assertInstanceOf(Volume::class, $volume);
        $this->assertEqualsWithDelta(30.0, $volume->toUnit('m³'), 0.0001);
    }

    public function test_area_factory_from_length(): void
    {
        $length = new Length(5, 'm');
        $area = Area::fromLength($length);

        $this->assertInstanceOf(Area::class, $area);
        $this->assertEqualsWithDelta(25.0, $area->toUnit('m²'), 0.0001);
    }

    public function test_area_factory_from_two_lengths(): void
    {
        $width = new Length(4, 'm');
        $height = new Length(5, 'm');
        $area = Area::fromLengths($width, $height);

        $this->assertInstanceOf(Area::class, $area);
        $this->assertEqualsWithDelta(20.0, $area->toUnit('m²'), 0.0001);
    }

    public function test_volume_factory_from_length(): void
    {
        $length = new Length(3, 'm');
        $volume = Volume::fromLength($length);

        $this->assertInstanceOf(Volume::class, $volume);
        $this->assertEqualsWithDelta(27.0, $volume->toUnit('m³'), 0.0001);
    }

    public function test_volume_factory_from_area_and_length(): void
    {
        $area = new Area(10, 'm²');
        $height = new Length(3, 'm');
        $volume = Volume::fromAreaAndLength($area, $height);

        $this->assertInstanceOf(Volume::class, $volume);
        $this->assertEqualsWithDelta(30.0, $volume->toUnit('m³'), 0.0001);
    }

    public function test_volume_factory_from_three_lengths(): void
    {
        $width = new Length(2, 'm');
        $height = new Length(3, 'm');
        $depth = new Length(4, 'm');
        $volume = Volume::fromLengths($width, $height, $depth);

        $this->assertInstanceOf(Volume::class, $volume);
        $this->assertEqualsWithDelta(24.0, $volume->toUnit('m³'), 0.0001);
    }

    public function test_mass_times_acceleration_creates_force(): void
    {
        $mass = new Mass(10, 'kg');
        $acceleration = new Acceleration(9.80665, 'm/s²');
        $force = $mass->multiply($acceleration);

        $this->assertInstanceOf(Force::class, $force);
        $this->assertEqualsWithDelta(98.0665, $force->toUnit('N'), 0.0001);
    }

    public function test_force_factory_from_mass_and_acceleration(): void
    {
        $mass = new Mass(10, 'kg');
        $acceleration = new Acceleration(9.80665, 'm/s²');
        $force = Force::fromMassAndAcceleration($mass, $acceleration);

        $this->assertInstanceOf(Force::class, $force);
        $this->assertEqualsWithDelta(98.0665, $force->toUnit('N'), 0.0001);
    }

    public function test_force_times_length_creates_energy(): void
    {
        $force = new Force(10, 'N');
        $length = new Length(5, 'm');
        $energy = $force->multiply($length);

        $this->assertInstanceOf(Energy::class, $energy);
        $this->assertEqualsWithDelta(50.0, $energy->toUnit('J'), 0.0001);
    }

    public function test_energy_factory_from_force_and_length(): void
    {
        $force = new Force(10, 'N');
        $length = new Length(5, 'm');
        $energy = Energy::fromForceAndLength($force, $length);

        $this->assertInstanceOf(Energy::class, $energy);
        $this->assertEqualsWithDelta(50.0, $energy->toUnit('J'), 0.0001);
    }

    public function test_energy_divided_by_time_creates_power(): void
    {
        $energy = new Energy(100, 'J');
        $time = new Time(10, 's');
        $power = $energy->divide($time);

        $this->assertInstanceOf(Power::class, $power);
        $this->assertEqualsWithDelta(10.0, $power->toUnit('W'), 0.0001);
    }

    public function test_power_factory_from_energy_and_time(): void
    {
        $energy = new Energy(100, 'J');
        $time = new Time(10, 's');
        $power = Power::fromEnergyAndTime($energy, $time);

        $this->assertInstanceOf(Power::class, $power);
        $this->assertEqualsWithDelta(10.0, $power->toUnit('W'), 0.0001);
    }

    public function test_force_divided_by_area_creates_pressure(): void
    {
        $force = new Force(100, 'N');
        $area = new Area(10, 'm²');
        $pressure = $force->divide($area);

        $this->assertInstanceOf(Pressure::class, $pressure);
        $this->assertEqualsWithDelta(10.0, $pressure->toUnit('Pa'), 0.0001);
    }

    public function test_pressure_factory_from_force_and_area(): void
    {
        $force = new Force(100, 'N');
        $area = new Area(10, 'm²');
        $pressure = Pressure::fromForceAndArea($force, $area);

        $this->assertInstanceOf(Pressure::class, $pressure);
        $this->assertEqualsWithDelta(10.0, $pressure->toUnit('Pa'), 0.0001);
    }

    public function test_length_divided_by_time_creates_velocity(): void
    {
        $length = new Length(100, 'm');
        $time = new Time(10, 's');
        $velocity = $length->divide($time);

        $this->assertInstanceOf(Velocity::class, $velocity);
        $this->assertEqualsWithDelta(10.0, $velocity->toUnit('m/s'), 0.0001);
    }

    public function test_velocity_divided_by_time_creates_acceleration(): void
    {
        $velocity = new Velocity(50, 'm/s');
        $time = new Time(5, 's');
        $acceleration = $velocity->divide($time);

        $this->assertInstanceOf(Acceleration::class, $acceleration);
        $this->assertEqualsWithDelta(10.0, $acceleration->toUnit('m/s²'), 0.0001);
    }

    public function test_volume_divided_by_length_creates_area(): void
    {
        $volume = new Volume(30, 'm³');
        $length = new Length(3, 'm');
        $area = $volume->divide($length);

        $this->assertInstanceOf(Area::class, $area);
        $this->assertEqualsWithDelta(10.0, $area->toUnit('m²'), 0.0001);
    }

    public function test_volume_divided_by_area_creates_length(): void
    {
        $volume = new Volume(30, 'm³');
        $area = new Area(10, 'm²');
        $length = $volume->divide($area);

        $this->assertInstanceOf(Length::class, $length);
        $this->assertEqualsWithDelta(3.0, $length->toUnit('m'), 0.0001);
    }

    public function test_area_divided_by_length_creates_length(): void
    {
        $area = new Area(20, 'm²');
        $length = new Length(4, 'm');
        $result = $area->divide($length);

        $this->assertInstanceOf(Length::class, $result);
        $this->assertEqualsWithDelta(5.0, $result->toUnit('m'), 0.0001);
    }

    public function test_complex_calculation_chain(): void
    {
        // Calculate power needed to lift an object
        // P = F × v = m × a × v
        $mass = new Mass(10, 'kg');
        $acceleration = new Acceleration(9.80665, 'm/s²');
        $force = $mass->multiply($acceleration);

        $velocity = new Velocity(2, 'm/s');
        $distance = new Length(10, 'm');
        $time = $distance->divide($velocity);

        $this->assertInstanceOf(Time::class, $time);
        $this->assertEqualsWithDelta(5.0, $time->toUnit('s'), 0.0001);

        $energy = $force->multiply($distance);
        $this->assertInstanceOf(Energy::class, $energy);
        $this->assertEqualsWithDelta(980.665, $energy->toUnit('J'), 0.001);

        $power = $energy->divide($time);
        $this->assertInstanceOf(Power::class, $power);
        $this->assertEqualsWithDelta(196.133, $power->toUnit('W'), 0.001);
    }

    public function test_throws_exception_for_unsupported_multiplication(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot multiply');

        $mass1 = new Mass(10, 'kg');
        $mass2 = new Mass(5, 'kg');
        $mass1->multiply($mass2);
    }

    public function test_throws_exception_for_unsupported_division(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot divide');

        $mass = new Mass(10, 'kg');
        $length = new Length(5, 'm');
        $mass->divide($length);
    }

    public function test_throws_exception_for_division_by_zero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot divide by zero');

        $length = new Length(10, 'm');
        $time = new Time(0, 's');
        $length->divide($time);
    }

    public function test_throws_exception_for_unsupported_power(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $mass = new Mass(10, 'kg');
        $mass->power(2);
    }

    public function test_power_of_zero_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot raise to power 0');

        $length = new Length(10, 'm');
        $length->power(0);
    }

    public function test_power_of_one_returns_clone(): void
    {
        $length = new Length(10, 'm');
        $result = $length->power(1);

        $this->assertInstanceOf(Length::class, $result);
        $this->assertEqualsWithDelta(10.0, $result->toUnit('m'), 0.0001);
        $this->assertNotSame($length, $result);
    }

    public function test_to_native_unit_converts_to_native_unit(): void
    {
        $length = new Length(100, 'cm');
        $native = $length->toNativeUnit();

        $this->assertInstanceOf(Length::class, $native);
        $this->assertEquals(1.0, $native->originalValue);
        $this->assertEquals('m', $native->nativeUnit->name);
        $this->assertEquals(1.0, $native->nativeValue);
    }

    public function test_length_divided_by_velocity_creates_time(): void
    {
        $length = new Length(100, 'm');
        $velocity = new Velocity(10, 'm/s');
        $time = $length->divide($velocity);

        $this->assertInstanceOf(Time::class, $time);
        $this->assertEqualsWithDelta(10.0, $time->toUnit('s'), 0.0001);
    }

    public function test_time_squared_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Time² is not a directly supported unit');

        $time = new Time(5, 's');
        $time->power(2);
    }

    public function test_to_string_returns_formatted_value(): void
    {
        $length = new Length(100, 'cm');

        $this->assertEquals('100 cm', (string) $length);
        $this->assertEquals('100 cm', $length->__toString());
    }

    public function test_derived_velocity_conversion_to_undefined_compound_unit_throws_exception(): void
    {
        // Test the specific case: Length / Time -> Velocity, then try to convert to ft/h
        // ft/h is not defined in Velocity class (only ft/s, mph, km/h, etc.)
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unit 'ft/h' not found");

        $velocity = new Length(100, 'm')->divide(new Time(10, 's'));
        $velocity->toUnit('ft/h');
    }

    public function test_derived_velocity_conversion_to_defined_compound_units_works(): void
    {
        // Test that conversions to DEFINED compound units work correctly
        $velocity = new Length(100, 'm')->divide(new Time(10, 's'));

        // These should work since they're defined in Velocity class
        $this->assertEqualsWithDelta(10.0, $velocity->toUnit('m/s'), 0.0001);
        $this->assertEqualsWithDelta(36.0, $velocity->toUnit('km/h'), 0.001);
        $this->assertEqualsWithDelta(22.369, $velocity->toUnit('mph'), 0.001);
        $this->assertEqualsWithDelta(32.808, $velocity->toUnit('ft/s'), 0.001);
    }

    public function test_derived_acceleration_conversion_to_undefined_compound_unit_throws_exception(): void
    {
        // Test with acceleration: Velocity / Time -> Acceleration, try converting to km/h²
        // km/h² is not defined in Acceleration class
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unit 'km/h²' not found");

        $acceleration = new Velocity(100, 'm/s')->divide(new Time(10, 's'));
        $acceleration->toUnit('km/h²');
    }

    public function test_derived_pressure_conversion_to_undefined_compound_unit_throws_exception(): void
    {
        // Test with pressure: Force / Area -> Pressure, try converting to N/cm²
        // N/cm² is not defined in Pressure class
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unit 'N/cm²' not found");

        $pressure = new Force(100, 'N')->divide(new Area(10, 'm²'));
        $pressure->toUnit('N/cm²');
    }

    public function test_derived_power_conversion_to_undefined_compound_unit_throws_exception(): void
    {
        // Test with power: Energy / Time -> Power, try converting to J/min
        // J/min is not defined in Power class
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unit 'J/min' not found");

        $power = new Energy(1000, 'J')->divide(new Time(10, 's'));
        $power->toUnit('J/min');
    }
}

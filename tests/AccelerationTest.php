<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Acceleration;
use Renfordt\UnitLib\Length;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\Time;
use Renfordt\UnitLib\UnitOfMeasurement;
use Renfordt\UnitLib\Velocity;

#[CoversClass(Acceleration::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
#[CoversClass(Velocity::class)]
#[CoversClass(Length::class)]
#[CoversClass(Time::class)]
final class AccelerationTest extends TestCase
{
    public function test_can_create_acceleration_from_velocity_and_time(): void
    {
        $velocity = new Velocity(100, 'm/s');
        $time = new Time(10, 's');

        $acceleration = Acceleration::fromVelocityAndTime($velocity, $time);

        $this->assertInstanceOf(Acceleration::class, $acceleration);
        $this->assertEqualsWithDelta(10.0, $acceleration->toUnit('m/s²'), 0.0001);
    }

    public function test_can_create_acceleration_using_divide_operation(): void
    {
        $velocity = new Velocity(50, 'm/s');
        $time = new Time(5, 's');

        $acceleration = $velocity->divide($time);

        $this->assertInstanceOf(Acceleration::class, $acceleration);
        $this->assertEqualsWithDelta(10.0, $acceleration->toUnit('m/s²'), 0.0001);
    }

    public function test_can_create_acceleration_from_length_and_time(): void
    {
        $length = new Length(100, 'm');
        $time = new Time(10, 's');

        $acceleration = Acceleration::fromLengthAndTime($length, $time);

        $this->assertInstanceOf(Acceleration::class, $acceleration);
        $this->assertEqualsWithDelta(1.0, $acceleration->toUnit('m/s²'), 0.0001);
    }

    public function test_can_convert_to_standard_gravity(): void
    {
        $acceleration = new Acceleration(9.80665, 'm/s²');

        $this->assertEqualsWithDelta(1.0, $acceleration->toUnit('g'), 0.0001);
    }

    public function test_can_convert_from_standard_gravity(): void
    {
        $acceleration = new Acceleration(2, 'g');

        $this->assertEqualsWithDelta(19.6133, $acceleration->toUnit('m/s²'), 0.0001);
    }

    public function test_can_convert_feet_per_second_squared(): void
    {
        $acceleration = new Acceleration(10, 'ft/s²');

        $this->assertEqualsWithDelta(3.048, $acceleration->toUnit('m/s²'), 0.0001);
    }

    public function test_supports_galileo_unit(): void
    {
        $acceleration = new Acceleration(100, 'Gal');

        $this->assertEqualsWithDelta(1.0, $acceleration->toUnit('m/s²'), 0.0001);
    }

    public function test_native_unit_is_meters_per_second_squared(): void
    {
        $acceleration = new Acceleration(2, 'g');

        $this->assertEquals('m/s²', $acceleration->nativeUnit->name);
        $this->assertEqualsWithDelta(19.6133, $acceleration->nativeValue, 0.0001);
    }

    public function test_throws_exception_when_time_is_zero(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $length = new Length(100, 'm');
        $time = new Time(0, 's');

        Acceleration::fromLengthAndTime($length, $time);
    }

    public function test_get_si_base_unit_returns_correct_base(): void
    {
        $acceleration = new Acceleration(1, 'm/s²');

        // Verify that the SI base unit is correctly set
        $this->assertEquals('m/s²', $acceleration->nativeUnit->name);
    }
}

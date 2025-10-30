<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Length;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\Time;
use Renfordt\UnitLib\UnitOfMeasurement;
use Renfordt\UnitLib\Velocity;

#[CoversClass(Velocity::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
#[CoversClass(Length::class)]
#[CoversClass(Time::class)]
final class VelocityTest extends TestCase
{
    public function test_can_create_velocity_from_length_and_time(): void
    {
        $length = new Length(100, 'm');
        $time = new Time(10, 's');

        $velocity = Velocity::fromLengthAndTime($length, $time);

        $this->assertInstanceOf(Velocity::class, $velocity);
        $this->assertEqualsWithDelta(10.0, $velocity->toUnit('m/s'), 0.0001);
    }

    public function test_can_create_velocity_using_divide_operation(): void
    {
        $length = new Length(1000, 'm');
        $time = new Time(100, 's');

        $velocity = $length->divide($time);

        $this->assertInstanceOf(Velocity::class, $velocity);
        $this->assertEqualsWithDelta(10.0, $velocity->toUnit('m/s'), 0.0001);
    }

    public function test_can_convert_between_velocity_units(): void
    {
        $velocity = new Velocity(100, 'km/h');

        $this->assertEqualsWithDelta(27.7778, $velocity->toUnit('m/s'), 0.001);
        $this->assertEqualsWithDelta(62.137, $velocity->toUnit('mph'), 0.001);
        $this->assertEqualsWithDelta(91.134, $velocity->toUnit('ft/s'), 0.001);
    }

    public function test_supports_common_velocity_aliases(): void
    {
        $velocity1 = new Velocity(10, 'mps');
        $velocity2 = new Velocity(10, 'meter per second');
        $velocity3 = new Velocity(10, 'm/s');

        $this->assertEqualsWithDelta(10.0, $velocity1->toUnit('m/s'), 0.0001);
        $this->assertEqualsWithDelta(10.0, $velocity2->toUnit('m/s'), 0.0001);
        $this->assertEqualsWithDelta(10.0, $velocity3->toUnit('m/s'), 0.0001);
    }

    public function test_can_convert_knots(): void
    {
        $velocity = new Velocity(10, 'kt');

        $this->assertEqualsWithDelta(5.144444, $velocity->toUnit('m/s'), 0.0001);
        $this->assertEqualsWithDelta(18.52, $velocity->toUnit('km/h'), 0.01);
    }

    public function test_velocity_with_si_prefixes(): void
    {
        // Test with kilometer per hour
        $velocity = new Velocity(1, 'km/h');

        $this->assertEqualsWithDelta(0.277778, $velocity->toUnit('m/s'), 0.0001);
    }

    public function test_native_unit_is_meters_per_second(): void
    {
        $velocity = new Velocity(100, 'km/h');

        $this->assertEquals('m/s', $velocity->nativeUnit->name);
        $this->assertEqualsWithDelta(27.7778, $velocity->nativeValue, 0.001);
    }

    public function test_get_si_base_unit_returns_correct_base(): void
    {
        $velocity = new Velocity(1, 'm/s');

        // Verify that the SI base unit is correctly set
        $this->assertEquals('m/s', $velocity->nativeUnit->name);
    }
}

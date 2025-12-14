<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Acceleration;
use Renfordt\UnitLib\AmountOfSubstance;
use Renfordt\UnitLib\Angle;
use Renfordt\UnitLib\Area;
use Renfordt\UnitLib\Capacitance;
use Renfordt\UnitLib\Charge;
use Renfordt\UnitLib\Current;
use Renfordt\UnitLib\Density;
use Renfordt\UnitLib\Energy;
use Renfordt\UnitLib\Force;
use Renfordt\UnitLib\Frequency;
use Renfordt\UnitLib\Inductance;
use Renfordt\UnitLib\Length;
use Renfordt\UnitLib\LuminousIntensity;
use Renfordt\UnitLib\MagneticFlux;
use Renfordt\UnitLib\Mass;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\Power;
use Renfordt\UnitLib\Pressure;
use Renfordt\UnitLib\Resistance;
use Renfordt\UnitLib\Temperature;
use Renfordt\UnitLib\Time;
use Renfordt\UnitLib\Torque;
use Renfordt\UnitLib\UnitOfMeasurement;
use Renfordt\UnitLib\Velocity;
use Renfordt\UnitLib\Voltage;
use Renfordt\UnitLib\Volume;

#[CoversClass(PhysicalQuantity::class)]
#[UsesClass(Length::class)]
#[UsesClass(Mass::class)]
#[UsesClass(Time::class)]
#[UsesClass(Temperature::class)]
#[UsesClass(Area::class)]
#[UsesClass(Volume::class)]
#[UsesClass(Force::class)]
#[UsesClass(Energy::class)]
#[UsesClass(Power::class)]
#[UsesClass(Pressure::class)]
#[UsesClass(Velocity::class)]
#[UsesClass(Acceleration::class)]
#[UsesClass(Current::class)]
#[UsesClass(Voltage::class)]
#[UsesClass(Resistance::class)]
#[UsesClass(LuminousIntensity::class)]
#[UsesClass(AmountOfSubstance::class)]
#[UsesClass(Angle::class)]
#[UsesClass(Frequency::class)]
#[UsesClass(Charge::class)]
#[UsesClass(Density::class)]
#[UsesClass(Torque::class)]
#[UsesClass(Capacitance::class)]
#[UsesClass(Inductance::class)]
#[UsesClass(MagneticFlux::class)]
#[UsesClass(UnitOfMeasurement::class)]
final class ParseTest extends TestCase
{
    public function test_parse_basic_format(): void
    {
        $quantity = PhysicalQuantity::parse('10 m', Length::class);

        $this->assertInstanceOf(Length::class, $quantity);
        $this->assertEquals(10, $quantity->originalValue);
    }

    public function test_parse_with_decimal(): void
    {
        $quantity = PhysicalQuantity::parse('5.5 meters', Length::class);

        $this->assertInstanceOf(Length::class, $quantity);
        $this->assertEquals(5.5, $quantity->originalValue);
    }

    public function test_parse_without_space(): void
    {
        $quantity = PhysicalQuantity::parse('100km', Length::class);

        $this->assertInstanceOf(Length::class, $quantity);
        $this->assertEquals(100, $quantity->originalValue);
    }

    public function test_parse_with_multiple_spaces(): void
    {
        $quantity = PhysicalQuantity::parse('  10   m  ', Length::class);

        $this->assertInstanceOf(Length::class, $quantity);
        $this->assertEquals(10, $quantity->originalValue);
    }

    public function test_parse_negative_value(): void
    {
        $quantity = PhysicalQuantity::parse('-40 °C', Temperature::class);

        $this->assertInstanceOf(Temperature::class, $quantity);
        $this->assertEquals(-40, $quantity->originalValue);
    }

    public function test_parse_positive_sign(): void
    {
        $quantity = PhysicalQuantity::parse('+10 m', Length::class);

        $this->assertInstanceOf(Length::class, $quantity);
        $this->assertEquals(10, $quantity->originalValue);
    }

    public function test_parse_with_aliases(): void
    {
        $quantity1 = PhysicalQuantity::parse('100 meters', Length::class);
        $quantity2 = PhysicalQuantity::parse('100 m', Length::class);

        $this->assertEquals($quantity1->nativeValue, $quantity2->nativeValue);
    }

    public function test_parse_with_expected_class(): void
    {
        $quantity = PhysicalQuantity::parse('5 kg', Mass::class);

        $this->assertInstanceOf(Mass::class, $quantity);
        $this->assertEquals(5, $quantity->originalValue);
    }

    public function test_parse_auto_detects_quantity_type(): void
    {
        $length = PhysicalQuantity::parse('10 m');
        $mass = PhysicalQuantity::parse('5 kg');
        $temp = PhysicalQuantity::parse('25 °C');

        $this->assertInstanceOf(Length::class, $length);
        $this->assertInstanceOf(Mass::class, $mass);
        $this->assertInstanceOf(Temperature::class, $temp);
    }

    public function test_parse_throws_exception_for_invalid_format(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid format');

        PhysicalQuantity::parse('invalid');
    }

    public function test_parse_throws_exception_for_missing_unit(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('no unit specified');

        PhysicalQuantity::parse('100 ');
    }

    public function test_parse_throws_exception_for_unknown_unit(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not recognized');

        PhysicalQuantity::parse('100 unknown_unit');
    }

    public function test_parse_throws_exception_for_wrong_expected_class(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        PhysicalQuantity::parse('100 kg', Length::class);
    }


    public function test_parse_si_prefix_units(): void
    {
        $length = PhysicalQuantity::parse('5 km', Length::class);

        $this->assertEquals(5, $length->originalValue);
        $this->assertEquals(5000, $length->nativeValue);
    }

    public function test_parse_composite_units(): void
    {
        $velocity = PhysicalQuantity::parse('100 km/h');

        $this->assertInstanceOf(\Renfordt\UnitLib\Velocity::class, $velocity);
    }

    public function test_parse_preserves_original_unit(): void
    {
        $length = PhysicalQuantity::parse('100 cm', Length::class);

        $this->assertEquals('100 cm', (string) $length);
    }
}

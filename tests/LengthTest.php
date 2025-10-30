<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Length;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Length::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
class LengthTest extends TestCase
{
    public function test_creates_length_with_meters(): void
    {
        $length = new Length(10, 'm');
        $this->assertEquals(10, $length->originalValue);
    }

    public function test_converts_meters_to_kilometers(): void
    {
        $length = new Length(1000, 'm');
        $this->assertEquals(1, $length->toUnit('km'));
    }

    public function test_converts_kilometers_to_meters(): void
    {
        $length = new Length(1, 'km');
        $this->assertEquals(1000, $length->toUnit('m'));
    }

    public function test_converts_meters_to_centimeters(): void
    {
        $length = new Length(1, 'm');
        $this->assertEquals(100, $length->toUnit('cm'));
    }

    public function test_converts_centimeters_to_meters(): void
    {
        $length = new Length(100, 'cm');
        $this->assertEquals(1, $length->toUnit('m'));
    }

    public function test_converts_meters_to_millimeters(): void
    {
        $length = new Length(1, 'm');
        $this->assertEquals(1000, $length->toUnit('mm'));
    }

    public function test_converts_millimeters_to_meters(): void
    {
        $length = new Length(1000, 'mm');
        $this->assertEquals(1, $length->toUnit('m'));
    }

    public function test_converts_meters_to_feet(): void
    {
        $length = new Length(1, 'm');
        $this->assertEqualsWithDelta(3.28084, $length->toUnit('ft'), 0.00001);
    }

    public function test_converts_feet_to_meters(): void
    {
        $length = new Length(1, 'ft');
        $this->assertEqualsWithDelta(0.3048, $length->toUnit('m'), 0.00001);
    }

    public function test_converts_meters_to_inches(): void
    {
        $length = new Length(1, 'm');
        $this->assertEqualsWithDelta(39.3701, $length->toUnit('in'), 0.0001);
    }

    public function test_converts_inches_to_centimeters(): void
    {
        $length = new Length(1, 'in');
        $this->assertEquals(2.54, $length->toUnit('cm'));
    }

    public function test_converts_feet_to_inches(): void
    {
        $length = new Length(1, 'ft');
        $this->assertEqualsWithDelta(12, $length->toUnit('in'), 0.0000001);
    }

    public function test_converts_yards_to_meters(): void
    {
        $length = new Length(1, 'yd');
        $this->assertEquals(0.9144, $length->toUnit('m'));
    }

    public function test_converts_meters_to_yards(): void
    {
        $length = new Length(1, 'm');
        $this->assertEqualsWithDelta(1.09361, $length->toUnit('yd'), 0.00001);
    }

    public function test_converts_miles_to_kilometers(): void
    {
        $length = new Length(1, 'mi');
        $this->assertEqualsWithDelta(1.609344, $length->toUnit('km'), 0.000001);
    }

    public function test_converts_kilometers_to_miles(): void
    {
        $length = new Length(1, 'km');
        $this->assertEqualsWithDelta(0.621371, $length->toUnit('mi'), 0.000001);
    }

    public function test_converts_nautical_miles_to_kilometers(): void
    {
        $length = new Length(1, 'nmi');
        $this->assertEquals(1.852, $length->toUnit('km'));
    }

    public function test_uses_unit_aliases(): void
    {
        $length1 = new Length(1, 'meter');
        $length2 = new Length(1, 'metre');
        $length3 = new Length(1, 'm');

        $this->assertEquals(100, $length1->toUnit('cm'));
        $this->assertEquals(100, $length2->toUnit('cm'));
        $this->assertEquals(100, $length3->toUnit('cm'));
    }

    public function test_throws_exception_for_invalid_unit(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Length(1, 'xyz123');
    }

    public function test_native_value_is_in_meters(): void
    {
        $length = new Length(100, 'cm');
        $this->assertEquals(1, $length->nativeValue);
    }

    public function test_to_string_shows_original_value_and_unit(): void
    {
        $length = new Length(100, 'cm');
        $this->assertEquals('100 cm', (string) $length);
    }

    public function test_add_lengths_in_different_units(): void
    {
        $length1 = new Length(1, 'm');
        $length2 = new Length(50, 'cm');
        $result = $length1->add($length2);

        $this->assertEquals(1.5, $result->toUnit('m'));
    }

    public function test_subtract_lengths_in_different_units(): void
    {
        $length1 = new Length(1, 'm');
        $length2 = new Length(50, 'cm');
        $result = $length1->subtract($length2);

        $this->assertEquals(0.5, $result->toUnit('m'));
    }

    public function test_to_unit_accepts_unit_of_measurement_object(): void
    {
        // Test the else branch in toUnit() when passing UnitOfMeasurement object
        // This tests PhysicalQuantity.php:118-120
        $length = new Length(1000, 'mm');
        $meterUnit = new UnitOfMeasurement('m', 1);

        $result = $length->toUnit($meterUnit);

        $this->assertEquals(1, $result);
    }

    public function test_to_native_unit_returns_new_instance_in_native_unit(): void
    {
        // Test PhysicalQuantity::toNativeUnit() method
        $length = new Length(100, 'cm');
        $nativeLength = $length->toNativeUnit();

        $this->assertEquals(1, $nativeLength->originalValue);
        $this->assertEquals(1, $nativeLength->nativeValue);
        $this->assertEquals('m', $nativeLength->nativeUnit->name);
    }
}

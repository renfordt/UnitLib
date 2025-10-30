<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Volume;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Volume::class)]
#[CoversClass(UnitOfMeasurement::class)]
class VolumeTest extends TestCase
{
    public function test_creates_volume_with_cubic_meters(): void
    {
        $volume = new Volume(10, 'm³');
        $this->assertEquals(10, $volume->originalValue);
    }

    public function test_converts_cubic_meters_to_liters(): void
    {
        $volume = new Volume(1, 'm³');
        $this->assertEquals(1000, $volume->toUnit('L'));
    }

    public function test_converts_liters_to_milliliters(): void
    {
        $volume = new Volume(1, 'L');
        $this->assertEqualsWithDelta(1000, $volume->toUnit('mL'), 0.0000001);
    }

    public function test_converts_milliliters_to_liters(): void
    {
        $volume = new Volume(1000, 'mL');
        $this->assertEquals(1, $volume->toUnit('L'));
    }

    public function test_converts_cubic_meters_to_cubic_centimeters(): void
    {
        $volume = new Volume(1, 'm³');
        $this->assertEquals(1000000, $volume->toUnit('cm³'));
    }

    public function test_converts_cubic_feet_to_cubic_meters(): void
    {
        $volume = new Volume(1, 'ft³');
        $this->assertEqualsWithDelta(0.028316846592, $volume->toUnit('m³'), 0.000000000001);
    }

    public function test_converts_cubic_meters_to_cubic_feet(): void
    {
        $volume = new Volume(1, 'm³');
        $this->assertEqualsWithDelta(35.3147, $volume->toUnit('ft³'), 0.0001);
    }

    public function test_converts_cubic_inches_to_milliliters(): void
    {
        $volume = new Volume(1, 'in³');
        $this->assertEqualsWithDelta(16.387064, $volume->toUnit('mL'), 0.000001);
    }

    public function test_converts_gallons_to_liters(): void
    {
        $volume = new Volume(1, 'gal');
        $this->assertEqualsWithDelta(3.785411784, $volume->toUnit('L'), 0.000000001);
    }

    public function test_converts_liters_to_gallons(): void
    {
        $volume = new Volume(1, 'L');
        $this->assertEqualsWithDelta(0.264172, $volume->toUnit('gal'), 0.000001);
    }

    public function test_converts_quarts_to_liters(): void
    {
        $volume = new Volume(1, 'qt');
        $this->assertEqualsWithDelta(0.946352946, $volume->toUnit('L'), 0.000000001);
    }

    public function test_converts_pints_to_milliliters(): void
    {
        $volume = new Volume(1, 'pt');
        $this->assertEqualsWithDelta(473.176473, $volume->toUnit('mL'), 0.000001);
    }

    public function test_converts_cups_to_milliliters(): void
    {
        $volume = new Volume(1, 'cup');
        $this->assertEqualsWithDelta(236.5882365, $volume->toUnit('mL'), 0.0000001);
    }

    public function test_converts_fluid_ounces_to_milliliters(): void
    {
        $volume = new Volume(1, 'fl oz');
        $this->assertEqualsWithDelta(29.5735295625, $volume->toUnit('mL'), 0.0000000001);
    }

    public function test_converts_imperial_gallons_to_liters(): void
    {
        $volume = new Volume(1, 'imp gal');
        $this->assertEqualsWithDelta(4.54609, $volume->toUnit('L'), 0.00001);
    }

    public function test_converts_imperial_pints_to_milliliters(): void
    {
        $volume = new Volume(1, 'imp pt');
        $this->assertEqualsWithDelta(568.26125, $volume->toUnit('mL'), 0.00001);
    }

    public function test_converts_us_gallons_to_imperial_gallons(): void
    {
        $volume = new Volume(1, 'gal');
        $this->assertEqualsWithDelta(0.832674, $volume->toUnit('imp gal'), 0.000001);
    }

    public function test_converts_cubic_yards_to_cubic_meters(): void
    {
        $volume = new Volume(1, 'yd³');
        $this->assertEqualsWithDelta(0.764554857984, $volume->toUnit('m³'), 0.000000000001);
    }

    public function test_uses_volume_aliases(): void
    {
        $volume1 = new Volume(1, 'L');
        $volume2 = new Volume(1, 'liter');
        $volume3 = new Volume(1, 'litre');

        $this->assertEqualsWithDelta(1000, $volume1->toUnit('mL'), 0.0000001);
        $this->assertEqualsWithDelta(1000, $volume2->toUnit('mL'), 0.0000001);
        $this->assertEqualsWithDelta(1000, $volume3->toUnit('mL'), 0.0000001);
    }

    public function test_native_value_is_in_cubic_meters(): void
    {
        $volume = new Volume(1000, 'L');
        $this->assertEquals(1, $volume->nativeValue);
    }

    public function test_add_volumes_in_different_units(): void
    {
        $volume1 = new Volume(1, 'L');
        $volume2 = new Volume(500, 'mL');
        $result = $volume1->add($volume2);

        $this->assertEquals(1.5, $result->toUnit('L'));
    }

    public function test_subtract_volumes_in_different_units(): void
    {
        $volume1 = new Volume(1, 'L');
        $volume2 = new Volume(500, 'mL');
        $result = $volume1->subtract($volume2);

        $this->assertEquals(0.5, $result->toUnit('L'));
    }
}

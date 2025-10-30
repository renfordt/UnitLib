<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Area;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Area::class)]
#[CoversClass(UnitOfMeasurement::class)]
class AreaTest extends TestCase
{
    public function test_creates_area_with_square_meters(): void
    {
        $area = new Area(10, 'm²');
        $this->assertEquals(10, $area->originalValue);
    }

    public function test_converts_square_meters_to_square_centimeters(): void
    {
        $area = new Area(1, 'm²');
        $this->assertEquals(10000, $area->toUnit('cm²'));
    }

    public function test_converts_square_kilometers_to_square_meters(): void
    {
        $area = new Area(1, 'km²');
        $this->assertEquals(1000000, $area->toUnit('m²'));
    }

    public function test_converts_hectares_to_square_meters(): void
    {
        $area = new Area(1, 'ha');
        $this->assertEquals(10000, $area->toUnit('m²'));
    }

    public function test_converts_ares_to_square_meters(): void
    {
        $area = new Area(1, 'a');
        $this->assertEquals(100, $area->toUnit('m²'));
    }

    public function test_converts_square_feet_to_square_meters(): void
    {
        $area = new Area(1, 'ft²');
        $this->assertEqualsWithDelta(0.09290304, $area->toUnit('m²'), 0.00000001);
    }

    public function test_converts_square_meters_to_square_feet(): void
    {
        $area = new Area(1, 'm²');
        $this->assertEqualsWithDelta(10.7639, $area->toUnit('ft²'), 0.0001);
    }

    public function test_converts_square_inches_to_square_centimeters(): void
    {
        $area = new Area(1, 'in²');
        $this->assertEqualsWithDelta(6.4516, $area->toUnit('cm²'), 0.0001);
    }

    public function test_converts_square_yards_to_square_meters(): void
    {
        $area = new Area(1, 'yd²');
        $this->assertEqualsWithDelta(0.83612736, $area->toUnit('m²'), 0.00000001);
    }

    public function test_converts_acres_to_square_meters(): void
    {
        $area = new Area(1, 'ac');
        $this->assertEqualsWithDelta(4046.8564224, $area->toUnit('m²'), 0.0000001);
    }

    public function test_converts_acres_to_hectares(): void
    {
        $area = new Area(1, 'ac');
        $this->assertEqualsWithDelta(0.40468564224, $area->toUnit('ha'), 0.00000000001);
    }

    public function test_converts_square_miles_to_square_kilometers(): void
    {
        $area = new Area(1, 'mi²');
        $this->assertEqualsWithDelta(2.589988110336, $area->toUnit('km²'), 0.000000000001);
    }

    public function test_uses_area_aliases(): void
    {
        $area1 = new Area(1, 'm²');
        $area2 = new Area(1, 'm2');
        $area3 = new Area(1, 'square meter');

        $this->assertEquals(10000, $area1->toUnit('cm²'));
        $this->assertEquals(10000, $area2->toUnit('cm²'));
        $this->assertEquals(10000, $area3->toUnit('cm²'));
    }

    public function test_native_value_is_in_square_meters(): void
    {
        $area = new Area(1, 'ha');
        $this->assertEquals(10000, $area->nativeValue);
    }

    public function test_add_areas_in_different_units(): void
    {
        $area1 = new Area(1, 'm²');
        $area2 = new Area(5000, 'cm²');
        $result = $area1->add($area2);

        $this->assertEquals(1.5, $result->toUnit('m²'));
    }

    public function test_subtract_areas_in_different_units(): void
    {
        $area1 = new Area(1, 'm²');
        $area2 = new Area(5000, 'cm²');
        $result = $area1->subtract($area2);

        $this->assertEquals(0.5, $result->toUnit('m²'));
    }
}

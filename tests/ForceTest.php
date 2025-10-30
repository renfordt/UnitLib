<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Force;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Force::class)]
#[CoversClass(UnitOfMeasurement::class)]
class ForceTest extends TestCase
{
    public function test_creates_force_with_newtons(): void
    {
        $force = new Force(10, 'N');
        $this->assertEquals(10, $force->originalValue);
    }

    public function test_converts_newtons_to_kilonewtons(): void
    {
        $force = new Force(1000, 'N');
        $this->assertEquals(1, $force->toUnit('kN'));
    }

    public function test_converts_kilonewtons_to_newtons(): void
    {
        $force = new Force(1, 'kN');
        $this->assertEquals(1000, $force->toUnit('N'));
    }

    public function test_converts_newtons_to_millinewtons(): void
    {
        $force = new Force(1, 'N');
        $this->assertEquals(1000, $force->toUnit('mN'));
    }

    public function test_converts_dynes_to_newtons(): void
    {
        $force = new Force(100000, 'dyn');
        $this->assertEquals(1, $force->toUnit('N'));
    }

    public function test_converts_newtons_to_dynes(): void
    {
        $force = new Force(1, 'N');
        $this->assertEqualsWithDelta(100000, $force->toUnit('dyn'), 0.0001);
    }

    public function test_converts_kilogram_force_to_newtons(): void
    {
        $force = new Force(1, 'kgf');
        $this->assertEqualsWithDelta(9.80665, $force->toUnit('N'), 0.00001);
    }

    public function test_converts_newtons_to_kilogram_force(): void
    {
        $force = new Force(9.80665, 'N');
        $this->assertEqualsWithDelta(1, $force->toUnit('kgf'), 0.00001);
    }

    public function test_converts_pound_force_to_newtons(): void
    {
        $force = new Force(1, 'lbf');
        $this->assertEqualsWithDelta(4.4482216152605, $force->toUnit('N'), 0.0000000001);
    }

    public function test_converts_newtons_to_pound_force(): void
    {
        $force = new Force(10, 'N');
        $this->assertEqualsWithDelta(2.2481, $force->toUnit('lbf'), 0.0001);
    }

    public function test_converts_poundals_to_newtons(): void
    {
        $force = new Force(1, 'pdl');
        $this->assertEqualsWithDelta(0.138254954376, $force->toUnit('N'), 0.000000000001);
    }

    public function test_converts_newtons_to_poundals(): void
    {
        $force = new Force(1, 'N');
        $this->assertEqualsWithDelta(7.2330, $force->toUnit('pdl'), 0.0001);
    }

    public function test_uses_force_aliases(): void
    {
        $force1 = new Force(1, 'N');
        $force2 = new Force(1, 'newton');
        $force3 = new Force(1, 'newtons');

        $this->assertEquals(1000, $force1->toUnit('mN'));
        $this->assertEquals(1000, $force2->toUnit('mN'));
        $this->assertEquals(1000, $force3->toUnit('mN'));
    }

    public function test_native_value_is_in_newtons(): void
    {
        $force = new Force(1, 'kN');
        $this->assertEquals(1000, $force->nativeValue);
    }

    public function test_add_forces_in_different_units(): void
    {
        $force1 = new Force(1, 'kN');
        $force2 = new Force(500, 'N');
        $result = $force1->add($force2);

        $this->assertEquals(1.5, $result->toUnit('kN'));
    }

    public function test_subtract_forces_in_different_units(): void
    {
        $force1 = new Force(1, 'kN');
        $force2 = new Force(500, 'N');
        $result = $force1->subtract($force2);

        $this->assertEquals(0.5, $result->toUnit('kN'));
    }
}

<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Mass;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Mass::class)]
#[CoversClass(UnitOfMeasurement::class)]
class MassTest extends TestCase
{
    public function test_creates_mass_with_grams(): void
    {
        $mass = new Mass(100, 'g');
        $this->assertEquals(100, $mass->originalValue);
    }

    public function test_converts_grams_to_kilograms(): void
    {
        $mass = new Mass(1000, 'g');
        $this->assertEquals(1, $mass->toUnit('kg'));
    }

    public function test_converts_kilograms_to_grams(): void
    {
        $mass = new Mass(1, 'kg');
        $this->assertEquals(1000, $mass->toUnit('g'));
    }

    public function test_converts_grams_to_milligrams(): void
    {
        $mass = new Mass(1, 'g');
        $this->assertEquals(1000, $mass->toUnit('mg'));
    }

    public function test_converts_tonnes_to_kilograms(): void
    {
        $mass = new Mass(1, 't');
        $this->assertEquals(1000, $mass->toUnit('kg'));
    }

    public function test_converts_tonnes_to_grams(): void
    {
        $mass = new Mass(1, 't');
        $this->assertEquals(1000000, $mass->toUnit('g'));
    }

    public function test_converts_pounds_to_kilograms(): void
    {
        $mass = new Mass(1, 'lb');
        $this->assertEqualsWithDelta(0.45359237, $mass->toUnit('kg'), 0.00000001);
    }

    public function test_converts_kilograms_to_pounds(): void
    {
        $mass = new Mass(1, 'kg');
        $this->assertEqualsWithDelta(2.20462, $mass->toUnit('lb'), 0.00001);
    }

    public function test_converts_ounces_to_grams(): void
    {
        $mass = new Mass(1, 'oz');
        $this->assertEqualsWithDelta(28.349523125, $mass->toUnit('g'), 0.000000001);
    }

    public function test_converts_grams_to_ounces(): void
    {
        $mass = new Mass(100, 'g');
        $this->assertEqualsWithDelta(3.5274, $mass->toUnit('oz'), 0.0001);
    }

    public function test_converts_pounds_to_ounces(): void
    {
        $mass = new Mass(1, 'lb');
        $this->assertEqualsWithDelta(16, $mass->toUnit('oz'), 0.00001);
    }

    public function test_converts_stone_to_pounds(): void
    {
        $mass = new Mass(1, 'st');
        $this->assertEqualsWithDelta(14, $mass->toUnit('lb'), 0.00001);
    }

    public function test_converts_stone_to_kilograms(): void
    {
        $mass = new Mass(1, 'st');
        $this->assertEqualsWithDelta(6.35029318, $mass->toUnit('kg'), 0.00000001);
    }

    public function test_converts_short_ton_to_pounds(): void
    {
        $mass = new Mass(1, 'ton');
        $this->assertEqualsWithDelta(2000, $mass->toUnit('lb'), 0.001);
    }

    public function test_converts_short_ton_to_kilograms(): void
    {
        $mass = new Mass(1, 'ton');
        $this->assertEqualsWithDelta(907.18474, $mass->toUnit('kg'), 0.00001);
    }

    public function test_converts_long_ton_to_kilograms(): void
    {
        $mass = new Mass(1, 'long ton');
        $this->assertEqualsWithDelta(1016.0469088, $mass->toUnit('kg'), 0.0000001);
    }

    public function test_uses_mass_aliases(): void
    {
        $mass1 = new Mass(1, 'g');
        $mass2 = new Mass(1, 'gram');
        $mass3 = new Mass(1, 'grams');

        $this->assertEquals(1000, $mass1->toUnit('mg'));
        $this->assertEquals(1000, $mass2->toUnit('mg'));
        $this->assertEquals(1000, $mass3->toUnit('mg'));
    }

    public function test_native_value_is_in_grams(): void
    {
        $mass = new Mass(1, 'kg');
        $this->assertEquals(1000, $mass->nativeValue);
    }

    public function test_add_masses_in_different_units(): void
    {
        $mass1 = new Mass(1, 'kg');
        $mass2 = new Mass(500, 'g');
        $result = $mass1->add($mass2);

        $this->assertEquals(1.5, $result->toUnit('kg'));
    }

    public function test_subtract_masses_in_different_units(): void
    {
        $mass1 = new Mass(1, 'kg');
        $mass2 = new Mass(500, 'g');
        $result = $mass1->subtract($mass2);

        $this->assertEquals(0.5, $result->toUnit('kg'));
    }
}

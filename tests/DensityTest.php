<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Density;
use Renfordt\UnitLib\Mass;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\UnitOfMeasurement;
use Renfordt\UnitLib\Volume;

#[CoversClass(Density::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
#[CoversClass(Mass::class)]
#[CoversClass(Volume::class)]
final class DensityTest extends TestCase
{
    public function test_creates_density_with_kg_per_m3(): void
    {
        $density = new Density(1000, 'kg/m³');

        $this->assertEquals(1000, $density->originalValue);
    }

    public function test_converts_g_per_cm3_to_kg_per_m3(): void
    {
        $density = new Density(1, 'g/cm³');

        $this->assertEquals(1000, $density->toUnit('kg/m³'));
    }

    public function test_converts_kg_per_m3_to_g_per_cm3(): void
    {
        $density = new Density(1000, 'kg/m³');

        $this->assertEqualsWithDelta(1, $density->toUnit('g/cm³'), 0.00001);
    }

    public function test_factory_from_mass_and_volume(): void
    {
        $mass = new Mass(2000, 'g');  // 2 kg
        $volume = new Volume(0.002, 'm³');  // 2 liters

        $density = Density::fromMassAndVolume($mass, $volume);

        $this->assertInstanceOf(Density::class, $density);
        $this->assertEquals(1000, $density->toUnit('kg/m³'));
    }

    public function test_converts_to_lb_per_ft3(): void
    {
        $density = new Density(1000, 'kg/m³');

        $this->assertEqualsWithDelta(62.428, $density->toUnit('lb/ft³'), 0.01);
    }

    public function test_converts_lb_per_ft3_to_kg_per_m3(): void
    {
        $density = new Density(62.428, 'lb/ft³');

        $this->assertEqualsWithDelta(1000, $density->toUnit('kg/m³'), 0.1);
    }

    public function test_converts_to_g_per_l(): void
    {
        $density = new Density(1, 'kg/m³');

        $this->assertEquals(1, $density->toUnit('g/L'));
    }

    public function test_throws_exception_for_zero_volume(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('zero volume');

        $mass = new Mass(1000, 'g');
        $volume = new Volume(0, 'm³');

        Density::fromMassAndVolume($mass, $volume);
    }

    public function test_uses_unit_aliases(): void
    {
        $density1 = new Density(100, 'kg/m³');
        $density2 = new Density(100, 'kg/m3');
        $density3 = new Density(100, 'kilogram per cubic meter');

        $this->assertEquals($density1->nativeValue, $density2->nativeValue);
        $this->assertEquals($density1->nativeValue, $density3->nativeValue);
    }

    public function test_native_value_is_in_kg_per_m3(): void
    {
        $density = new Density(1, 'g/cm³');

        $this->assertEquals(1000, $density->nativeValue);
        $this->assertEquals('kg/m³', $density->nativeUnit->name);
    }

    public function test_to_string_representation(): void
    {
        $density = new Density(1, 'g/cm³');

        $this->assertEquals('1 g/cm³', (string) $density);
    }

    public function test_throws_exception_for_invalid_unit(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Density(100, 'invalid_unit');
    }
}

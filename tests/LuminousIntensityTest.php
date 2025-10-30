<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\LuminousIntensity;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(LuminousIntensity::class)]
#[CoversClass(UnitOfMeasurement::class)]
class LuminousIntensityTest extends TestCase
{
    public function test_creates_luminous_intensity_with_candelas(): void
    {
        $light = new LuminousIntensity(100, 'cd');
        $this->assertEquals(100, $light->originalValue);
    }

    public function test_converts_candelas_to_millicandelas(): void
    {
        $light = new LuminousIntensity(1, 'cd');
        $this->assertEquals(1000, $light->toUnit('mcd'));
    }

    public function test_converts_millicandelas_to_candelas(): void
    {
        $light = new LuminousIntensity(1000, 'mcd');
        $this->assertEquals(1, $light->toUnit('cd'));
    }

    public function test_converts_kilocandelas_to_candelas(): void
    {
        $light = new LuminousIntensity(1, 'kcd');
        $this->assertEquals(1000, $light->toUnit('cd'));
    }

    public function test_converts_candelas_to_kilocandelas(): void
    {
        $light = new LuminousIntensity(1000, 'cd');
        $this->assertEquals(1, $light->toUnit('kcd'));
    }

    public function test_converts_megacandelas_to_candelas(): void
    {
        $light = new LuminousIntensity(1, 'Mcd');
        $this->assertEquals(1000000, $light->toUnit('cd'));
    }

    public function test_converts_candelas_to_microcandelas(): void
    {
        $light = new LuminousIntensity(1, 'cd');
        $this->assertEquals(1000000, $light->toUnit('μcd'));
    }

    public function test_converts_microcandelas_to_candelas(): void
    {
        $light = new LuminousIntensity(1000000, 'μcd');
        $this->assertEquals(1, $light->toUnit('cd'));
    }

    public function test_converts_millicandelas_to_microcandelas(): void
    {
        $light = new LuminousIntensity(1, 'mcd');
        $this->assertEquals(1000, $light->toUnit('μcd'));
    }

    public function test_uses_luminous_intensity_aliases(): void
    {
        $light1 = new LuminousIntensity(1, 'cd');
        $light2 = new LuminousIntensity(1, 'candela');
        $light3 = new LuminousIntensity(1, 'candelas');

        $this->assertEquals(1000, $light1->toUnit('mcd'));
        $this->assertEquals(1000, $light2->toUnit('mcd'));
        $this->assertEquals(1000, $light3->toUnit('mcd'));
    }

    public function test_native_value_is_in_candelas(): void
    {
        $light = new LuminousIntensity(1000, 'mcd');
        $this->assertEquals(1, $light->nativeValue);
    }

    public function test_add_luminous_intensities_in_different_units(): void
    {
        $light1 = new LuminousIntensity(1, 'cd');
        $light2 = new LuminousIntensity(500, 'mcd');
        $result = $light1->add($light2);

        $this->assertEquals(1.5, $result->toUnit('cd'));
    }

    public function test_subtract_luminous_intensities_in_different_units(): void
    {
        $light1 = new LuminousIntensity(1, 'cd');
        $light2 = new LuminousIntensity(500, 'mcd');
        $result = $light1->subtract($light2);

        $this->assertEquals(0.5, $result->toUnit('cd'));
    }

    public function test_string_representation(): void
    {
        $light = new LuminousIntensity(100, 'cd');
        $this->assertEquals('100 cd', (string) $light);
    }
}

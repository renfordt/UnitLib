<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Energy;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Energy::class)]
#[CoversClass(UnitOfMeasurement::class)]
class EnergyTest extends TestCase
{
    public function test_creates_energy_with_joules(): void
    {
        $energy = new Energy(100, 'J');
        $this->assertEquals(100, $energy->originalValue);
    }

    public function test_converts_joules_to_kilojoules(): void
    {
        $energy = new Energy(1000, 'J');
        $this->assertEquals(1, $energy->toUnit('kJ'));
    }

    public function test_converts_kilojoules_to_joules(): void
    {
        $energy = new Energy(1, 'kJ');
        $this->assertEquals(1000, $energy->toUnit('J'));
    }

    public function test_converts_watt_hours_to_joules(): void
    {
        $energy = new Energy(1, 'Wh');
        $this->assertEquals(3600, $energy->toUnit('J'));
    }

    public function test_converts_kilowatt_hours_to_joules(): void
    {
        $energy = new Energy(1, 'kWh');
        $this->assertEquals(3600000, $energy->toUnit('J'));
    }

    public function test_converts_kilowatt_hours_to_megajoules(): void
    {
        $energy = new Energy(1, 'kWh');
        $this->assertEqualsWithDelta(3.6, $energy->toUnit('MJ'), 0.0001);
    }

    public function test_converts_calories_to_joules(): void
    {
        $energy = new Energy(1, 'cal');
        $this->assertEquals(4.184, $energy->toUnit('J'));
    }

    public function test_converts_kilocalories_to_joules(): void
    {
        $energy = new Energy(1, 'kcal');
        $this->assertEquals(4184, $energy->toUnit('J'));
    }

    public function test_converts_kilocalories_to_kilojoules(): void
    {
        $energy = new Energy(1, 'kcal');
        $this->assertEquals(4.184, $energy->toUnit('kJ'));
    }

    public function test_converts_btu_to_joules(): void
    {
        $energy = new Energy(1, 'BTU');
        $this->assertEqualsWithDelta(1055.05585262, $energy->toUnit('J'), 0.00000001);
    }

    public function test_converts_joules_to_btu(): void
    {
        $energy = new Energy(1055.05585262, 'J');
        $this->assertEqualsWithDelta(1, $energy->toUnit('BTU'), 0.00000001);
    }

    public function test_converts_electronvolts_to_joules(): void
    {
        $energy = new Energy(1, 'eV');
        $this->assertEqualsWithDelta(1.602176634e-19, $energy->toUnit('J'), 1e-27);
    }

    public function test_converts_ergs_to_joules(): void
    {
        $energy = new Energy(10000000, 'erg');
        $this->assertEquals(1, $energy->toUnit('J'));
    }

    public function test_converts_foot_pounds_to_joules(): void
    {
        $energy = new Energy(1, 'ft·lbf');
        $this->assertEqualsWithDelta(1.3558179483314004, $energy->toUnit('J'), 0.0000000000001);
    }

    public function test_uses_energy_aliases(): void
    {
        $energy1 = new Energy(1, 'J');
        $energy2 = new Energy(1, 'joule');
        $energy3 = new Energy(1, 'joules');

        $this->assertEquals(0.001, $energy1->toUnit('kJ'));
        $this->assertEquals(0.001, $energy2->toUnit('kJ'));
        $this->assertEquals(0.001, $energy3->toUnit('kJ'));
    }

    public function test_native_value_is_in_joules(): void
    {
        $energy = new Energy(1, 'kJ');
        $this->assertEquals(1000, $energy->nativeValue);
    }

    public function test_add_energies_in_different_units(): void
    {
        $energy1 = new Energy(1, 'kJ');
        $energy2 = new Energy(500, 'J');
        $result = $energy1->add($energy2);

        $this->assertEquals(1.5, $result->toUnit('kJ'));
    }

    public function test_subtract_energies_in_different_units(): void
    {
        $energy1 = new Energy(1, 'kJ');
        $energy2 = new Energy(500, 'J');
        $result = $energy1->subtract($energy2);

        $this->assertEquals(0.5, $result->toUnit('kJ'));
    }
}

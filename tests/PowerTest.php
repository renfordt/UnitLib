<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Power;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Power::class)]
#[CoversClass(UnitOfMeasurement::class)]
class PowerTest extends TestCase
{
    public function test_creates_power_with_watts(): void
    {
        $power = new Power(100, 'W');
        $this->assertEquals(100, $power->originalValue);
    }

    public function test_converts_watts_to_kilowatts(): void
    {
        $power = new Power(1000, 'W');
        $this->assertEquals(1, $power->toUnit('kW'));
    }

    public function test_converts_kilowatts_to_watts(): void
    {
        $power = new Power(1, 'kW');
        $this->assertEquals(1000, $power->toUnit('W'));
    }

    public function test_converts_watts_to_milliwatts(): void
    {
        $power = new Power(1, 'W');
        $this->assertEquals(1000, $power->toUnit('mW'));
    }

    public function test_converts_horsepower_to_watts(): void
    {
        $power = new Power(1, 'hp');
        $this->assertEqualsWithDelta(745.69987158227022, $power->toUnit('W'), 0.00000000001);
    }

    public function test_converts_watts_to_horsepower(): void
    {
        $power = new Power(745.69987158227022, 'W');
        $this->assertEqualsWithDelta(1, $power->toUnit('hp'), 0.00000000001);
    }

    public function test_converts_horsepower_to_kilowatts(): void
    {
        $power = new Power(1, 'hp');
        $this->assertEqualsWithDelta(0.74569987, $power->toUnit('kW'), 0.00000001);
    }

    public function test_converts_metric_horsepower_to_watts(): void
    {
        $power = new Power(1, 'PS');
        $this->assertEquals(735.49875, $power->toUnit('W'));
    }

    public function test_converts_electric_horsepower_to_watts(): void
    {
        $power = new Power(1, 'hp(E)');
        $this->assertEquals(746, $power->toUnit('W'));
    }

    public function test_converts_btu_per_hour_to_watts(): void
    {
        $power = new Power(1, 'BTU/h');
        $this->assertEqualsWithDelta(0.29307107017, $power->toUnit('W'), 0.00000000001);
    }

    public function test_converts_watts_to_btu_per_hour(): void
    {
        $power = new Power(1000, 'W');
        $this->assertEqualsWithDelta(3412.1416, $power->toUnit('BTU/h'), 0.0001);
    }

    public function test_converts_calorie_per_second_to_watts(): void
    {
        $power = new Power(1, 'cal/s');
        $this->assertEquals(4.184, $power->toUnit('W'));
    }

    public function test_converts_foot_pound_per_second_to_watts(): void
    {
        $power = new Power(1, 'ft·lbf/s');
        $this->assertEqualsWithDelta(1.3558179483314004, $power->toUnit('W'), 0.0000000000001);
    }

    public function test_uses_power_aliases(): void
    {
        $power1 = new Power(1, 'W');
        $power2 = new Power(1, 'watt');
        $power3 = new Power(1, 'watts');

        $this->assertEquals(0.001, $power1->toUnit('kW'));
        $this->assertEquals(0.001, $power2->toUnit('kW'));
        $this->assertEquals(0.001, $power3->toUnit('kW'));
    }

    public function test_native_value_is_in_watts(): void
    {
        $power = new Power(1, 'kW');
        $this->assertEquals(1000, $power->nativeValue);
    }

    public function test_add_powers_in_different_units(): void
    {
        $power1 = new Power(1, 'kW');
        $power2 = new Power(500, 'W');
        $result = $power1->add($power2);

        $this->assertEquals(1.5, $result->toUnit('kW'));
    }

    public function test_subtract_powers_in_different_units(): void
    {
        $power1 = new Power(1, 'kW');
        $power2 = new Power(500, 'W');
        $result = $power1->subtract($power2);

        $this->assertEquals(0.5, $result->toUnit('kW'));
    }
}

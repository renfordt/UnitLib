<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Charge;
use Renfordt\UnitLib\Current;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\Time;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Charge::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
#[CoversClass(Current::class)]
#[CoversClass(Time::class)]
final class ChargeTest extends TestCase
{
    public function test_creates_charge_with_coulombs(): void
    {
        $charge = new Charge(10, 'C');

        $this->assertEquals(10, $charge->originalValue);
    }

    public function test_converts_coulombs_to_millicoulombs(): void
    {
        $charge = new Charge(1, 'C');

        $this->assertEqualsWithDelta(1000, $charge->toUnit('mC'), 0.00001);
    }

    public function test_converts_millicoulombs_to_coulombs(): void
    {
        $charge = new Charge(1000, 'mC');

        $this->assertEquals(1, $charge->toUnit('C'));
    }

    public function test_factory_from_current_and_time(): void
    {
        $current = new Current(2, 'A');
        $time = new Time(5, 's');

        $charge = Charge::fromCurrentAndTime($current, $time);

        $this->assertInstanceOf(Charge::class, $charge);
        $this->assertEquals(10, $charge->toUnit('C'));
    }

    public function test_converts_to_ampere_hour(): void
    {
        $charge = new Charge(3600, 'C');

        $this->assertEqualsWithDelta(1, $charge->toUnit('Ah'), 0.00001);
    }

    public function test_converts_ampere_hour_to_coulombs(): void
    {
        $charge = new Charge(1, 'Ah');

        $this->assertEquals(3600, $charge->toUnit('C'));
    }

    public function test_converts_to_milliampere_hour(): void
    {
        $charge = new Charge(3.6, 'C');

        $this->assertEqualsWithDelta(1, $charge->toUnit('mAh'), 0.001);
    }

    public function test_si_prefix_conversions(): void
    {
        $charge = new Charge(1, 'kC');

        $this->assertEquals(1000, $charge->toUnit('C'));
        $this->assertEquals(1000000, $charge->toUnit('mC'));
    }

    public function test_current_times_time_creates_charge(): void
    {
        $current = new Current(5, 'A');
        $time = new Time(10, 's');

        $charge = $current->multiply($time);

        $this->assertInstanceOf(Charge::class, $charge);
        $this->assertEquals(50, $charge->toUnit('C'));
    }

    public function test_time_times_current_creates_charge(): void
    {
        $time = new Time(10, 's');
        $current = new Current(5, 'A');

        $charge = $time->multiply($current);

        $this->assertInstanceOf(Charge::class, $charge);
        $this->assertEquals(50, $charge->toUnit('C'));
    }

    public function test_uses_unit_aliases(): void
    {
        $charge1 = new Charge(100, 'C');
        $charge2 = new Charge(100, 'coulomb');
        $charge3 = new Charge(100, 'coulombs');

        $this->assertEquals($charge1->nativeValue, $charge2->nativeValue);
        $this->assertEquals($charge1->nativeValue, $charge3->nativeValue);
    }

    public function test_native_value_is_in_coulombs(): void
    {
        $charge = new Charge(5, 'Ah');

        $this->assertEquals(18000, $charge->nativeValue);
        $this->assertEquals('C', $charge->nativeUnit->name);
    }

    public function test_to_string_representation(): void
    {
        $charge = new Charge(100, 'mAh');

        $this->assertEquals('100 mAh', (string) $charge);
    }

    public function test_throws_exception_for_invalid_unit(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Charge(100, 'invalid_unit');
    }
}

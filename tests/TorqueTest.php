<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Force;
use Renfordt\UnitLib\Length;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\Torque;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Torque::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
#[CoversClass(Force::class)]
#[CoversClass(Length::class)]
final class TorqueTest extends TestCase
{
    public function test_creates_torque_with_newton_meters(): void
    {
        $torque = new Torque(50, 'N⋅m');

        $this->assertEquals(50, $torque->originalValue);
    }

    public function test_converts_to_lbf_ft(): void
    {
        $torque = new Torque(1.3558179483314, 'N⋅m');

        $this->assertEqualsWithDelta(1, $torque->toUnit('lbf⋅ft'), 0.00001);
    }

    public function test_converts_lbf_ft_to_nm(): void
    {
        $torque = new Torque(1, 'lbf⋅ft');

        $this->assertEqualsWithDelta(1.3558179483314, $torque->toUnit('N⋅m'), 0.00001);
    }

    public function test_converts_to_lbf_in(): void
    {
        $torque = new Torque(0.1129848290276, 'N⋅m');

        $this->assertEqualsWithDelta(1, $torque->toUnit('lbf⋅in'), 0.00001);
    }

    public function test_factory_from_force_and_length(): void
    {
        $force = new Force(10, 'N');
        $length = new Length(0.5, 'm');

        $torque = Torque::fromForceAndLength($force, $length);

        $this->assertInstanceOf(Torque::class, $torque);
        $this->assertEquals(5, $torque->toUnit('N⋅m'));
    }

    public function test_si_prefix_conversions(): void
    {
        $torque = new Torque(1, 'kN⋅m');

        $this->assertEquals(1000, $torque->toUnit('N⋅m'));
    }

    public function test_uses_unit_aliases(): void
    {
        $torque1 = new Torque(100, 'N⋅m');
        $torque2 = new Torque(100, 'Nm');
        $torque3 = new Torque(100, 'N·m');
        $torque4 = new Torque(100, 'newton-meter');

        $this->assertEquals($torque1->nativeValue, $torque2->nativeValue);
        $this->assertEquals($torque1->nativeValue, $torque3->nativeValue);
        $this->assertEquals($torque1->nativeValue, $torque4->nativeValue);
    }

    public function test_native_value_is_in_newton_meters(): void
    {
        $torque = new Torque(100, 'lbf⋅ft');

        $this->assertEqualsWithDelta(135.58179483314, $torque->nativeValue, 0.00001);
        $this->assertEquals('N⋅m', $torque->nativeUnit->name);
    }

    public function test_to_string_representation(): void
    {
        $torque = new Torque(50, 'lbf⋅ft');

        $this->assertEquals('50 lbf⋅ft', (string) $torque);
    }

    public function test_throws_exception_for_invalid_unit(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Torque(100, 'invalid_unit');
    }
}

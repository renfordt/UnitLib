<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Energy;
use Renfordt\UnitLib\Force;
use Renfordt\UnitLib\Length;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\Torque;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(Force::class)]
#[CoversClass(Length::class)]
#[CoversClass(Energy::class)]
#[CoversClass(Torque::class)]
#[CoversClass(UnitOfMeasurement::class)]
final class EnergyTorqueAmbiguityTest extends TestCase
{
    public function test_force_times_length_creates_energy_by_default(): void
    {
        $force = new Force(10, 'N');
        $length = new Length(5, 'm');

        $result = $force->multiply($length);

        $this->assertInstanceOf(Energy::class, $result);
        $this->assertEquals(50, $result->toUnit('J'));
    }

    public function test_force_times_length_creates_torque_when_specified(): void
    {
        $force = new Force(10, 'N');
        $length = new Length(5, 'm');

        $result = $force->multiply($length, Torque::class);

        $this->assertInstanceOf(Torque::class, $result);
        $this->assertEquals(50, $result->toUnit('N⋅m'));
    }

    public function test_length_times_force_creates_energy_by_default(): void
    {
        $length = new Length(5, 'm');
        $force = new Force(10, 'N');

        $result = $length->multiply($force);

        $this->assertInstanceOf(Energy::class, $result);
        $this->assertEquals(50, $result->toUnit('J'));
    }

    public function test_length_times_force_creates_torque_when_specified(): void
    {
        $length = new Length(5, 'm');
        $force = new Force(10, 'N');

        $result = $length->multiply($force, Torque::class);

        $this->assertInstanceOf(Torque::class, $result);
        $this->assertEquals(50, $result->toUnit('N⋅m'));
    }

    public function test_factory_method_still_works_for_torque(): void
    {
        $force = new Force(10, 'N');
        $length = new Length(5, 'm');

        $torque = Torque::fromForceAndLength($force, $length);

        $this->assertInstanceOf(Torque::class, $torque);
        $this->assertEquals(50, $torque->toUnit('N⋅m'));
    }

    public function test_different_units_work_correctly(): void
    {
        $force = new Force(100, 'N');
        $length = new Length(50, 'cm');

        $energy = $force->multiply($length);
        $torque = $force->multiply($length, Torque::class);

        $this->assertInstanceOf(Energy::class, $energy);
        $this->assertInstanceOf(Torque::class, $torque);
        $this->assertEquals(50, $energy->toUnit('J'));
        $this->assertEquals(50, $torque->toUnit('N⋅m'));
    }
}

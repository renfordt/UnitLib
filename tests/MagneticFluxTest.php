<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\MagneticFlux;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\Time;
use Renfordt\UnitLib\UnitOfMeasurement;
use Renfordt\UnitLib\Voltage;

#[CoversClass(MagneticFlux::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
#[CoversClass(Voltage::class)]
#[CoversClass(Time::class)]
final class MagneticFluxTest extends TestCase
{
    public function test_creates_magnetic_flux_with_webers(): void
    {
        $flux = new MagneticFlux(10, 'Wb');

        $this->assertEquals(10, $flux->originalValue);
    }

    public function test_converts_webers_to_milliwebers(): void
    {
        $flux = new MagneticFlux(1, 'Wb');

        $this->assertEquals(1000, $flux->toUnit('mWb'));
    }

    public function test_converts_milliwebers_to_webers(): void
    {
        $flux = new MagneticFlux(1000, 'mWb');

        $this->assertEquals(1, $flux->toUnit('Wb'));
    }

    public function test_converts_to_maxwell(): void
    {
        $flux = new MagneticFlux(1, 'Wb');

        $this->assertEquals(100000000, $flux->toUnit('Mx'));
    }

    public function test_converts_maxwell_to_webers(): void
    {
        $flux = new MagneticFlux(100000000, 'Mx');

        $this->assertEquals(1, $flux->toUnit('Wb'));
    }

    public function test_factory_from_voltage_and_time(): void
    {
        $voltage = new Voltage(10, 'V');
        $time = new Time(5, 's');

        $flux = MagneticFlux::fromVoltageAndTime($voltage, $time);

        $this->assertInstanceOf(MagneticFlux::class, $flux);
        $this->assertEquals(50, $flux->toUnit('Wb'));
    }

    public function test_voltage_times_time_creates_magnetic_flux(): void
    {
        $voltage = new Voltage(5, 'V');
        $time = new Time(10, 's');

        $flux = $voltage->multiply($time);

        $this->assertInstanceOf(MagneticFlux::class, $flux);
        $this->assertEquals(50, $flux->toUnit('Wb'));
    }

    public function test_time_times_voltage_creates_magnetic_flux(): void
    {
        $time = new Time(10, 's');
        $voltage = new Voltage(5, 'V');

        $flux = $time->multiply($voltage);

        $this->assertInstanceOf(MagneticFlux::class, $flux);
        $this->assertEquals(50, $flux->toUnit('Wb'));
    }

    public function test_si_prefix_conversions(): void
    {
        $flux = new MagneticFlux(1, 'kWb');

        $this->assertEquals(1000, $flux->toUnit('Wb'));
        $this->assertEquals(1000000, $flux->toUnit('mWb'));
    }

    public function test_uses_unit_aliases(): void
    {
        $flux1 = new MagneticFlux(100, 'Wb');
        $flux2 = new MagneticFlux(100, 'weber');
        $flux3 = new MagneticFlux(100, 'webers');

        $this->assertEquals($flux1->nativeValue, $flux2->nativeValue);
        $this->assertEquals($flux1->nativeValue, $flux3->nativeValue);
    }

    public function test_maxwell_aliases(): void
    {
        $flux1 = new MagneticFlux(1000, 'Mx');
        $flux2 = new MagneticFlux(1000, 'maxwell');
        $flux3 = new MagneticFlux(1000, 'maxwells');

        $this->assertEquals($flux1->nativeValue, $flux2->nativeValue);
        $this->assertEquals($flux1->nativeValue, $flux3->nativeValue);
    }

    public function test_native_value_is_in_webers(): void
    {
        $flux = new MagneticFlux(500, 'mWb');

        $this->assertEquals(0.5, $flux->nativeValue);
        $this->assertEquals('Wb', $flux->nativeUnit->name);
    }

    public function test_to_string_representation(): void
    {
        $flux = new MagneticFlux(100, 'mWb');

        $this->assertEquals('100 mWb', (string) $flux);
    }

    public function test_throws_exception_for_invalid_unit(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new MagneticFlux(100, 'invalid_unit');
    }
}

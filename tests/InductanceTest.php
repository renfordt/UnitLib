<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Current;
use Renfordt\UnitLib\Inductance;
use Renfordt\UnitLib\MagneticFlux;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Inductance::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
#[CoversClass(MagneticFlux::class)]
#[CoversClass(Current::class)]
final class InductanceTest extends TestCase
{
    public function test_creates_inductance_with_henries(): void
    {
        $inductance = new Inductance(10, 'H');

        $this->assertEquals(10, $inductance->originalValue);
    }

    public function test_converts_henries_to_millihenries(): void
    {
        $inductance = new Inductance(1, 'H');

        $this->assertEquals(1000, $inductance->toUnit('mH'));
    }

    public function test_converts_millihenries_to_henries(): void
    {
        $inductance = new Inductance(1000, 'mH');

        $this->assertEquals(1, $inductance->toUnit('H'));
    }

    public function test_magnetic_flux_divided_by_current_creates_inductance(): void
    {
        $flux = new MagneticFlux(20, 'Wb');
        $current = new Current(10, 'A');

        $inductance = $flux->divide($current);

        $this->assertInstanceOf(Inductance::class, $inductance);
        $this->assertEquals(2, $inductance->toUnit('H'));
    }

    public function test_si_prefix_conversions(): void
    {
        $inductance = new Inductance(1, 'kH');

        $this->assertEquals(1000, $inductance->toUnit('H'));
        $this->assertEquals(1000000, $inductance->toUnit('mH'));
    }

    public function test_uses_unit_aliases(): void
    {
        $inductance1 = new Inductance(100, 'H');
        $inductance2 = new Inductance(100, 'henry');
        $inductance3 = new Inductance(100, 'henries');
        $inductance4 = new Inductance(100, 'henrys');

        $this->assertEquals($inductance1->nativeValue, $inductance2->nativeValue);
        $this->assertEquals($inductance1->nativeValue, $inductance3->nativeValue);
        $this->assertEquals($inductance1->nativeValue, $inductance4->nativeValue);
    }

    public function test_native_value_is_in_henries(): void
    {
        $inductance = new Inductance(500, 'mH');

        $this->assertEquals(0.5, $inductance->nativeValue);
        $this->assertEquals('H', $inductance->nativeUnit->name);
    }

    public function test_to_string_representation(): void
    {
        $inductance = new Inductance(100, 'mH');

        $this->assertEquals('100 mH', (string) $inductance);
    }

    public function test_throws_exception_for_invalid_unit(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Inductance(100, 'invalid_unit');
    }
}

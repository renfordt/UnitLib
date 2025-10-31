<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\Resistance;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Resistance::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
class ResistanceTest extends TestCase
{
    public function test_can_create_resistance_in_ohms(): void
    {
        $resistance = new Resistance(100, 'Ω');
        self::assertSame(100.0, $resistance->toUnit('Ω'));
    }

    public function test_can_create_resistance_with_aliases(): void
    {
        $resistance1 = new Resistance(50, 'ohm');
        $resistance2 = new Resistance(50, 'ohms');

        self::assertSame(50.0, $resistance1->toUnit('Ω'));
        self::assertSame(50.0, $resistance2->toUnit('Ω'));
    }

    public function test_can_convert_to_kilohms(): void
    {
        $resistance = new Resistance(1000, 'Ω');
        self::assertSame(1.0, $resistance->toUnit('kΩ'));
    }

    public function test_can_convert_to_megohms(): void
    {
        $resistance = new Resistance(1_000_000, 'Ω');
        self::assertSame(1.0, $resistance->toUnit('MΩ'));
    }

    public function test_can_convert_to_milliohms(): void
    {
        $resistance = new Resistance(1, 'Ω');
        self::assertSame(1000.0, $resistance->toUnit('mΩ'));
    }

    public function test_can_convert_from_kilohms_to_ohms(): void
    {
        $resistance = new Resistance(2.5, 'kΩ');
        self::assertSame(2500.0, $resistance->toUnit('Ω'));
    }

    public function test_can_convert_from_megohms_to_kilohms(): void
    {
        $resistance = new Resistance(1, 'MΩ');
        self::assertSame(1000.0, $resistance->toUnit('kΩ'));
    }

    public function test_can_add_resistances(): void
    {
        $resistance1 = new Resistance(100, 'Ω');
        $resistance2 = new Resistance(200, 'Ω');
        $result = $resistance1->add($resistance2);

        self::assertSame(300.0, $result->toUnit('Ω'));
    }

    public function test_can_add_resistances_with_different_units(): void
    {
        $resistance1 = new Resistance(1, 'kΩ');
        $resistance2 = new Resistance(500, 'Ω');
        $result = $resistance1->add($resistance2);

        self::assertSame(1500.0, $result->toUnit('Ω'));
    }

    public function test_can_subtract_resistances(): void
    {
        $resistance1 = new Resistance(500, 'Ω');
        $resistance2 = new Resistance(200, 'Ω');
        $result = $resistance1->subtract($resistance2);

        self::assertSame(300.0, $result->toUnit('Ω'));
    }

    public function test_can_subtract_resistances_with_different_units(): void
    {
        $resistance1 = new Resistance(2, 'kΩ');
        $resistance2 = new Resistance(500, 'Ω');
        $result = $resistance1->subtract($resistance2);

        self::assertSame(1500.0, $result->toUnit('Ω'));
    }

    public function test_string_representation(): void
    {
        $resistance = new Resistance(470, 'Ω');
        self::assertSame('470 Ω', (string) $resistance);
    }

    public function test_string_representation_with_prefix(): void
    {
        $resistance = new Resistance(10, 'kΩ');
        self::assertSame('10 kΩ', (string) $resistance);
    }

    public function test_all_si_prefixes_work(): void
    {
        $resistance = new Resistance(1, 'Ω');

        // Test various SI prefixes
        self::assertSame(1000.0, $resistance->toUnit('mΩ'));
        self::assertSame(1_000_000.0, $resistance->toUnit('μΩ'));
        self::assertSame(0.001, $resistance->toUnit('kΩ'));
        self::assertSame(0.000001, $resistance->toUnit('MΩ'));
    }

    public function test_can_convert_between_different_prefixes(): void
    {
        $resistance = new Resistance(1, 'MΩ');
        self::assertSame(1_000_000_000.0, $resistance->toUnit('mΩ'));
    }

    public function test_common_resistor_values(): void
    {
        // Common resistor values in electronics
        $resistor1 = new Resistance(10, 'kΩ');
        self::assertSame(10_000.0, $resistor1->toUnit('Ω'));

        $resistor2 = new Resistance(4.7, 'kΩ');
        self::assertSame(4700.0, $resistor2->toUnit('Ω'));

        $resistor3 = new Resistance(1, 'MΩ');
        self::assertSame(1000.0, $resistor3->toUnit('kΩ'));
    }

    public function test_immutability_after_operations(): void
    {
        $original = new Resistance(100, 'Ω');
        $result = $original->add(new Resistance(50, 'Ω'));

        self::assertSame(100.0, $original->toUnit('Ω'));
        self::assertSame(150.0, $result->toUnit('Ω'));
    }
}

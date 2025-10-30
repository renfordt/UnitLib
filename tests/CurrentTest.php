<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Current;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Current::class)]
#[CoversClass(UnitOfMeasurement::class)]
class CurrentTest extends TestCase
{
    public function test_creates_current_with_amperes(): void
    {
        $current = new Current(5, 'A');
        $this->assertEquals(5, $current->originalValue);
    }

    public function test_converts_amperes_to_milliamperes(): void
    {
        $current = new Current(1, 'A');
        $this->assertEquals(1000, $current->toUnit('mA'));
    }

    public function test_converts_milliamperes_to_amperes(): void
    {
        $current = new Current(1000, 'mA');
        $this->assertEquals(1, $current->toUnit('A'));
    }

    public function test_converts_amperes_to_microamperes(): void
    {
        $current = new Current(1, 'A');
        $this->assertEquals(1000000, $current->toUnit('μA'));
    }

    public function test_converts_microamperes_to_amperes(): void
    {
        $current = new Current(1000000, 'μA');
        $this->assertEquals(1, $current->toUnit('A'));
    }

    public function test_converts_amperes_to_nanoamperes(): void
    {
        $current = new Current(1, 'A');
        $this->assertEquals(1000000000, $current->toUnit('nA'));
    }

    public function test_converts_nanoamperes_to_amperes(): void
    {
        $current = new Current(1000000000, 'nA');
        $this->assertEquals(1, $current->toUnit('A'));
    }

    public function test_converts_kiloamperes_to_amperes(): void
    {
        $current = new Current(1, 'kA');
        $this->assertEquals(1000, $current->toUnit('A'));
    }

    public function test_converts_amperes_to_kiloamperes(): void
    {
        $current = new Current(1000, 'A');
        $this->assertEquals(1, $current->toUnit('kA'));
    }

    public function test_converts_megaamperes_to_amperes(): void
    {
        $current = new Current(1, 'MA');
        $this->assertEquals(1000000, $current->toUnit('A'));
    }

    public function test_converts_milliamperes_to_microamperes(): void
    {
        $current = new Current(1, 'mA');
        $this->assertEquals(1000, $current->toUnit('μA'));
    }

    public function test_uses_current_aliases(): void
    {
        $current1 = new Current(1, 'A');
        $current2 = new Current(1, 'ampere');
        $current3 = new Current(1, 'amperes');
        $current4 = new Current(1, 'amp');
        $current5 = new Current(1, 'amps');

        $this->assertEquals(1000, $current1->toUnit('mA'));
        $this->assertEquals(1000, $current2->toUnit('mA'));
        $this->assertEquals(1000, $current3->toUnit('mA'));
        $this->assertEquals(1000, $current4->toUnit('mA'));
        $this->assertEquals(1000, $current5->toUnit('mA'));
    }

    public function test_native_value_is_in_amperes(): void
    {
        $current = new Current(1000, 'mA');
        $this->assertEquals(1, $current->nativeValue);
    }

    public function test_add_currents_in_different_units(): void
    {
        $current1 = new Current(1, 'A');
        $current2 = new Current(500, 'mA');
        $result = $current1->add($current2);

        $this->assertEquals(1.5, $result->toUnit('A'));
    }

    public function test_subtract_currents_in_different_units(): void
    {
        $current1 = new Current(1, 'A');
        $current2 = new Current(500, 'mA');
        $result = $current1->subtract($current2);

        $this->assertEquals(0.5, $result->toUnit('A'));
    }

    public function test_string_representation(): void
    {
        $current = new Current(5, 'A');
        $this->assertEquals('5 A', (string) $current);
    }
}

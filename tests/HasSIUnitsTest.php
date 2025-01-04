<?php

namespace Renfordt\UnitLib\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\HasSIUnits;

#[CoversClass(HasSIUnits::class)]
class HasSIUnitsTest extends TestCase
{
    use HasSIUnits;

    public function test_convert_si_unit_converts_base_unit_to_kilo(): void
    {
        $value = 1000; // 1000 meters
        $fromUnit = 'm';
        $toUnit = 'km';

        $result = $this->convertSIUnit($value, $fromUnit, $toUnit);

        $this->assertEquals(1, $result);
    }

    public function test_convert_si_unit_converts_kilo_to_milli(): void
    {
        $value = 1; // 1 kilometer
        $fromUnit = 'km';
        $toUnit = 'mm';

        $result = $this->convertSIUnit($value, $fromUnit, $toUnit);

        $this->assertEquals(1000000, $result);
    }

    public function test_convert_si_unit_keeps_base_units_same(): void
    {
        $value = 500; // 500 meters
        $fromUnit = 'm';
        $toUnit = 'm';

        $result = $this->convertSIUnit($value, $fromUnit, $toUnit);

        $this->assertEquals(500, $result);
    }

    public function test_convert_si_unit_converts_milli_to_micro(): void
    {
        $value = 1; // 1 millimeter
        $fromUnit = 'mm';
        $toUnit = 'μm';

        $result = $this->convertSIUnit($value, $fromUnit, $toUnit);

        $this->assertEquals(1000, $result);
    }

    public function test_convert_si_unit_throws_exception_for_invalid_from_unit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid metric unit provided');

        $this->convertSIUnit(1, 'invalidUnit', 'm');
    }

    public function test_convert_si_unit_throws_exception_for_invalid_to_unit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid metric unit provided');

        $this->convertSIUnit(1, 'm', 'invalidUnit');
    }

    public function test_convert_si_unit_handles_large_exponents(): void
    {
        $value = 1; // 1 yotta meter
        $fromUnit = 'Ym';
        $toUnit = 'mm';

        $result = $this->convertSIUnit($value, $fromUnit, $toUnit);

        $this->assertEquals(10 ** 27, $result);
    }

    public function test_convert_si_unit_handles_small_exponents(): void
    {
        $value = 1; // 1 femto meter
        $fromUnit = 'fm';
        $toUnit = 'pm';

        $result = $this->convertSIUnit($value, $fromUnit, $toUnit);

        $this->assertEquals(1000, $result);
    }
}

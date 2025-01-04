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

    public function testConvertSIUnitConvertsBaseUnitToKilo(): void
    {
        $value = 1000; // 1000 meters
        $fromUnit = 'm';
        $toUnit = 'km';

        $result = $this->convertSIUnit($value, $fromUnit, $toUnit);

        $this->assertEquals(1, $result);
    }

    public function testConvertSIUnitConvertsKiloToMilli(): void
    {
        $value = 1; // 1 kilometer
        $fromUnit = 'km';
        $toUnit = 'mm';

        $result = $this->convertSIUnit($value, $fromUnit, $toUnit);

        $this->assertEquals(1000000, $result);
    }

    public function testConvertSIUnitKeepsBaseUnitsSame(): void
    {
        $value = 500; // 500 meters
        $fromUnit = 'm';
        $toUnit = 'm';

        $result = $this->convertSIUnit($value, $fromUnit, $toUnit);

        $this->assertEquals(500, $result);
    }

    public function testConvertSIUnitConvertsMilliToMicro(): void
    {
        $value = 1; // 1 millimeter
        $fromUnit = 'mm';
        $toUnit = 'μm';

        $result = $this->convertSIUnit($value, $fromUnit, $toUnit);

        $this->assertEquals(1000, $result);
    }

    public function testConvertSIUnitThrowsExceptionForInvalidFromUnit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid metric unit provided');

        $this->convertSIUnit(1, 'invalidUnit', 'm');
    }

    public function testConvertSIUnitThrowsExceptionForInvalidToUnit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid metric unit provided');

        $this->convertSIUnit(1, 'm', 'invalidUnit');
    }

    public function testConvertSIUnitHandlesLargeExponents(): void
    {
        $value = 1; // 1 yotta meter
        $fromUnit = 'Ym';
        $toUnit = 'mm';

        $result = $this->convertSIUnit($value, $fromUnit, $toUnit);

        $this->assertEquals(10 ** 27, $result);
    }

    public function testConvertSIUnitHandlesSmallExponents(): void
    {
        $value = 1; // 1 femto meter
        $fromUnit = 'fm';
        $toUnit = 'pm';

        $result = $this->convertSIUnit($value, $fromUnit, $toUnit);

        $this->assertEquals(1000, $result);
    }
}
<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\UnitOfMeasurement;
use Renfordt\UnitLib\Voltage;

#[CoversClass(Voltage::class)]
#[CoversClass(UnitOfMeasurement::class)]
class VoltageTest extends TestCase
{
    public function test_creates_voltage_with_volts(): void
    {
        $voltage = new Voltage(12, 'V');
        $this->assertEquals(12, $voltage->originalValue);
    }

    public function test_converts_volts_to_millivolts(): void
    {
        $voltage = new Voltage(1, 'V');
        $this->assertEquals(1000, $voltage->toUnit('mV'));
    }

    public function test_converts_millivolts_to_volts(): void
    {
        $voltage = new Voltage(1000, 'mV');
        $this->assertEquals(1, $voltage->toUnit('V'));
    }

    public function test_converts_volts_to_microvolts(): void
    {
        $voltage = new Voltage(1, 'V');
        $this->assertEquals(1000000, $voltage->toUnit('μV'));
    }

    public function test_converts_microvolts_to_volts(): void
    {
        $voltage = new Voltage(1000000, 'μV');
        $this->assertEquals(1, $voltage->toUnit('V'));
    }

    public function test_converts_kilovolts_to_volts(): void
    {
        $voltage = new Voltage(1, 'kV');
        $this->assertEquals(1000, $voltage->toUnit('V'));
    }

    public function test_converts_volts_to_kilovolts(): void
    {
        $voltage = new Voltage(1000, 'V');
        $this->assertEquals(1, $voltage->toUnit('kV'));
    }

    public function test_converts_megavolts_to_volts(): void
    {
        $voltage = new Voltage(1, 'MV');
        $this->assertEquals(1000000, $voltage->toUnit('V'));
    }

    public function test_converts_volts_to_megavolts(): void
    {
        $voltage = new Voltage(1000000, 'V');
        $this->assertEquals(1, $voltage->toUnit('MV'));
    }

    public function test_converts_gigavolts_to_volts(): void
    {
        $voltage = new Voltage(1, 'GV');
        $this->assertEquals(1000000000, $voltage->toUnit('V'));
    }

    public function test_converts_volts_to_gigavolts(): void
    {
        $voltage = new Voltage(1000000000, 'V');
        $this->assertEquals(1, $voltage->toUnit('GV'));
    }

    public function test_converts_millivolts_to_microvolts(): void
    {
        $voltage = new Voltage(1, 'mV');
        $this->assertEquals(1000, $voltage->toUnit('μV'));
    }

    public function test_uses_voltage_aliases(): void
    {
        $voltage1 = new Voltage(1, 'V');
        $voltage2 = new Voltage(1, 'volt');
        $voltage3 = new Voltage(1, 'volts');

        $this->assertEquals(1000, $voltage1->toUnit('mV'));
        $this->assertEquals(1000, $voltage2->toUnit('mV'));
        $this->assertEquals(1000, $voltage3->toUnit('mV'));
    }

    public function test_native_value_is_in_volts(): void
    {
        $voltage = new Voltage(1000, 'mV');
        $this->assertEquals(1, $voltage->nativeValue);
    }

    public function test_add_voltages_in_different_units(): void
    {
        $voltage1 = new Voltage(1, 'V');
        $voltage2 = new Voltage(500, 'mV');
        $result = $voltage1->add($voltage2);

        $this->assertEquals(1.5, $result->toUnit('V'));
    }

    public function test_subtract_voltages_in_different_units(): void
    {
        $voltage1 = new Voltage(1, 'V');
        $voltage2 = new Voltage(500, 'mV');
        $result = $voltage1->subtract($voltage2);

        $this->assertEquals(0.5, $result->toUnit('V'));
    }

    public function test_string_representation(): void
    {
        $voltage = new Voltage(12, 'V');
        $this->assertEquals('12 V', (string) $voltage);
    }
}

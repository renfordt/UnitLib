<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Frequency;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\Time;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Frequency::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
#[CoversClass(Time::class)]
final class FrequencyTest extends TestCase
{
    public function test_creates_frequency_with_hertz(): void
    {
        $frequency = new Frequency(50, 'Hz');

        $this->assertEquals(50, $frequency->originalValue);
    }

    public function test_converts_hz_to_khz(): void
    {
        $frequency = new Frequency(1000, 'Hz');

        $this->assertEqualsWithDelta(1, $frequency->toUnit('kHz'), 0.00001);
    }

    public function test_converts_khz_to_hz(): void
    {
        $frequency = new Frequency(1, 'kHz');

        $this->assertEquals(1000, $frequency->toUnit('Hz'));
    }

    public function test_converts_to_rpm(): void
    {
        $frequency = new Frequency(60, 'Hz');

        $this->assertEqualsWithDelta(3600, $frequency->toUnit('RPM'), 0.001);
    }

    public function test_converts_rpm_to_hz(): void
    {
        $frequency = new Frequency(60, 'RPM');

        $this->assertEqualsWithDelta(1, $frequency->toUnit('Hz'), 0.00001);
    }

    public function test_converts_to_bpm(): void
    {
        $frequency = new Frequency(1.5, 'Hz');

        $this->assertEqualsWithDelta(90, $frequency->toUnit('BPM'), 0.001);
    }

    public function test_factory_from_time_period(): void
    {
        $period = new Time(0.02, 's');  // 20ms period
        $frequency = Frequency::fromTimePeriod($period);

        $this->assertInstanceOf(Frequency::class, $frequency);
        $this->assertEqualsWithDelta(50, $frequency->toUnit('Hz'), 0.00001);
    }

    public function test_throws_exception_for_zero_period(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('zero time period');

        $period = new Time(0, 's');
        Frequency::fromTimePeriod($period);
    }

    public function test_si_prefix_conversions(): void
    {
        $frequency = new Frequency(1, 'MHz');

        $this->assertEquals(1000000, $frequency->toUnit('Hz'));
        $this->assertEquals(1000, $frequency->toUnit('kHz'));
    }

    public function test_to_string_representation(): void
    {
        $frequency = new Frequency(440, 'Hz');

        $this->assertEquals('440 Hz', (string) $frequency);
    }

    public function test_uses_unit_aliases(): void
    {
        $frequency1 = new Frequency(100, 'Hz');
        $frequency2 = new Frequency(100, 'hertz');
        $frequency3 = new Frequency(100, 'hz');

        $this->assertEquals($frequency1->nativeValue, $frequency2->nativeValue);
        $this->assertEquals($frequency1->nativeValue, $frequency3->nativeValue);
    }

    public function test_native_value_is_in_hertz(): void
    {
        $frequency = new Frequency(5, 'kHz');

        $this->assertEquals(5000, $frequency->nativeValue);
        $this->assertEquals('Hz', $frequency->nativeUnit->name);
    }

    public function test_throws_exception_for_invalid_unit(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Frequency(100, 'invalid_unit');
    }
}

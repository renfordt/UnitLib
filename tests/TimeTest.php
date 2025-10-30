<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Time;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Time::class)]
#[CoversClass(UnitOfMeasurement::class)]
class TimeTest extends TestCase
{
    public function test_creates_time_with_seconds(): void
    {
        $time = new Time(60, 's');
        $this->assertEquals(60, $time->originalValue);
    }

    public function test_converts_seconds_to_milliseconds(): void
    {
        $time = new Time(1, 's');
        $this->assertEquals(1000, $time->toUnit('ms'));
    }

    public function test_converts_milliseconds_to_seconds(): void
    {
        $time = new Time(1000, 'ms');
        $this->assertEquals(1, $time->toUnit('s'));
    }

    public function test_converts_seconds_to_microseconds(): void
    {
        $time = new Time(1, 's');
        $this->assertEquals(1000000, $time->toUnit('μs'));
    }

    public function test_converts_minutes_to_seconds(): void
    {
        $time = new Time(1, 'min');
        $this->assertEquals(60, $time->toUnit('s'));
    }

    public function test_converts_seconds_to_minutes(): void
    {
        $time = new Time(60, 's');
        $this->assertEquals(1, $time->toUnit('min'));
    }

    public function test_converts_hours_to_minutes(): void
    {
        $time = new Time(1, 'h');
        $this->assertEquals(60, $time->toUnit('min'));
    }

    public function test_converts_hours_to_seconds(): void
    {
        $time = new Time(1, 'h');
        $this->assertEquals(3600, $time->toUnit('s'));
    }

    public function test_converts_days_to_hours(): void
    {
        $time = new Time(1, 'd');
        $this->assertEquals(24, $time->toUnit('h'));
    }

    public function test_converts_days_to_seconds(): void
    {
        $time = new Time(1, 'd');
        $this->assertEquals(86400, $time->toUnit('s'));
    }

    public function test_converts_weeks_to_days(): void
    {
        $time = new Time(1, 'wk');
        $this->assertEquals(7, $time->toUnit('d'));
    }

    public function test_converts_weeks_to_hours(): void
    {
        $time = new Time(1, 'wk');
        $this->assertEquals(168, $time->toUnit('h'));
    }

    public function test_converts_years_to_days(): void
    {
        $time = new Time(1, 'yr');
        $this->assertEquals(365.25, $time->toUnit('d'));
    }

    public function test_converts_years_to_seconds(): void
    {
        $time = new Time(1, 'yr');
        $this->assertEquals(31557600, $time->toUnit('s'));
    }

    public function test_uses_time_aliases(): void
    {
        $time1 = new Time(1, 's');
        $time2 = new Time(1, 'sec');
        $time3 = new Time(1, 'second');

        $this->assertEquals(1000, $time1->toUnit('ms'));
        $this->assertEquals(1000, $time2->toUnit('ms'));
        $this->assertEquals(1000, $time3->toUnit('ms'));
    }

    public function test_native_value_is_in_seconds(): void
    {
        $time = new Time(1, 'min');
        $this->assertEquals(60, $time->nativeValue);
    }

    public function test_add_times_in_different_units(): void
    {
        $time1 = new Time(1, 'min');
        $time2 = new Time(30, 's');
        $result = $time1->add($time2);

        $this->assertEquals(1.5, $result->toUnit('min'));
    }

    public function test_subtract_times_in_different_units(): void
    {
        $time1 = new Time(1, 'min');
        $time2 = new Time(30, 's');
        $result = $time1->subtract($time2);

        $this->assertEquals(0.5, $result->toUnit('min'));
    }
}

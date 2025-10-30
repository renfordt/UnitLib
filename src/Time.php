<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

class Time extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 's';
    }

    protected function initialise(): void
    {
        // SI base unit
        $second = new UnitOfMeasurement('s', 1);
        $second->addAlias('sec');
        $second->addAlias('second');
        $second->addAlias('seconds');
        $this->addUnit($second);

        // Common time units
        $minute = new UnitOfMeasurement('min', 60);
        $minute->addAlias('minute');
        $minute->addAlias('minutes');
        $this->addUnit($minute);

        $hour = new UnitOfMeasurement('h', 3600);
        $hour->addAlias('hr');
        $hour->addAlias('hour');
        $hour->addAlias('hours');
        $this->addUnit($hour);

        $day = new UnitOfMeasurement('d', 86400);
        $day->addAlias('day');
        $day->addAlias('days');
        $this->addUnit($day);

        $week = new UnitOfMeasurement('wk', 604800);
        $week->addAlias('week');
        $week->addAlias('weeks');
        $this->addUnit($week);

        $year = new UnitOfMeasurement('yr', 31557600); // 365.25 days
        $year->addAlias('year');
        $year->addAlias('years');
        $this->addUnit($year);
    }
}

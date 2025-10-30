<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

class Length extends PhysicalQuantity
{
    use HasSIUnits;

    protected function initialise(): void
    {
        // Metric base unit (SI)
        $meter = new UnitOfMeasurement('m', 1);
        $meter->addAlias('meter');
        $meter->addAlias('meters');
        $meter->addAlias('metre');
        $meter->addAlias('metres');
        $this->addUnit($meter);

        // Imperial units
        $inch = new UnitOfMeasurement('in', 0.0254);
        $inch->addAlias('inch');
        $inch->addAlias('inches');
        $this->addUnit($inch);

        $foot = new UnitOfMeasurement('ft', 0.3048);
        $foot->addAlias('foot');
        $foot->addAlias('feet');
        $this->addUnit($foot);

        $yard = new UnitOfMeasurement('yd', 0.9144);
        $yard->addAlias('yard');
        $yard->addAlias('yards');
        $this->addUnit($yard);

        $mile = new UnitOfMeasurement('mi', 1609.344);
        $mile->addAlias('mile');
        $mile->addAlias('miles');
        $this->addUnit($mile);

        // Nautical units
        $nauticalMile = new UnitOfMeasurement('nmi', 1852);
        $nauticalMile->addAlias('nautical mile');
        $nauticalMile->addAlias('nautical miles');
        $this->addUnit($nauticalMile);
    }
}

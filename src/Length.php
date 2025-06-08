<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

class Length extends PhysicalQuantity
{
    use HasSIUnits;

    protected function initialise(): void
    {
        $meter = new UnitOfMeasurement('m', 1);
        $meter->addAlias('meter');
        $meter->addAlias('meters');
        $meter->addAlias('metre');
        $meter->addAlias('metres');

        $this->addUnit($meter);

        $foot = new UnitOfMeasurement('ft', 0.3048);
        $foot->addAlias('foot');
        $foot->addAlias('feet');
        $this->addUnit($foot);

        $inch = new UnitOfMeasurement('in', 0.0254);
        $inch->addAlias('inch');
        $inch->addAlias('inches');
        $this->addUnit($inch);

    }
}

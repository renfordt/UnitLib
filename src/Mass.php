<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

class Mass extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'g';
    }

    protected function initialise(): void
    {
        // Metric base unit (SI - but we use gram as base, kg is derived)
        $gram = new UnitOfMeasurement('g', 1);
        $gram->addAlias('gram');
        $gram->addAlias('grams');
        $gram->addAlias('gramme');
        $gram->addAlias('grammes');
        $this->addUnit($gram);

        // Metric ton (tonne)
        $tonne = new UnitOfMeasurement('t', 1000000);
        $tonne->addAlias('tonne');
        $tonne->addAlias('tonnes');
        $tonne->addAlias('metric ton');
        $tonne->addAlias('metric tons');
        $this->addUnit($tonne);

        // Imperial/US units
        $ounce = new UnitOfMeasurement('oz', 28.349523125);
        $ounce->addAlias('ounce');
        $ounce->addAlias('ounces');
        $this->addUnit($ounce);

        $pound = new UnitOfMeasurement('lb', 453.59237);
        $pound->addAlias('lbs');
        $pound->addAlias('pound');
        $pound->addAlias('pounds');
        $this->addUnit($pound);

        $stone = new UnitOfMeasurement('st', 6350.29318);
        $stone->addAlias('stone');
        $stone->addAlias('stones');
        $this->addUnit($stone);

        $shortTon = new UnitOfMeasurement('ton', 907184.74);
        $shortTon->addAlias('short ton');
        $shortTon->addAlias('US ton');
        $this->addUnit($shortTon);

        $longTon = new UnitOfMeasurement('long ton', 1016046.9088);
        $longTon->addAlias('imperial ton');
        $this->addUnit($longTon);
    }
}

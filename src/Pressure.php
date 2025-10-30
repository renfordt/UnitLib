<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

class Pressure extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'Pa';
    }

    /**
     * Create Pressure from Force / Area.
     */
    public static function fromForceAndArea(Force $force, Area $area): self
    {
        /** @var self */
        return $force->divide($area);
    }

    protected function initialise(): void
    {
        // SI base unit (Pascal)
        $pascal = new UnitOfMeasurement('Pa', 1);
        $pascal->addAlias('pascal');
        $pascal->addAlias('pascals');
        $this->addUnit($pascal);

        // Bar
        $bar = new UnitOfMeasurement('bar', 100000);
        $bar->addAlias('bars');
        $this->addUnit($bar);

        $millibar = new UnitOfMeasurement('mbar', 100);
        $millibar->addAlias('millibar');
        $millibar->addAlias('millibars');
        $this->addUnit($millibar);

        // Atmosphere
        $atmosphere = new UnitOfMeasurement('atm', 101325);
        $atmosphere->addAlias('atmosphere');
        $atmosphere->addAlias('atmospheres');
        $this->addUnit($atmosphere);

        // Torr and mmHg
        $torr = new UnitOfMeasurement('Torr', 133.322368421);
        $torr->addAlias('torr');
        $this->addUnit($torr);

        $mmHg = new UnitOfMeasurement('mmHg', 133.322368421);
        $mmHg->addAlias('millimeter of mercury');
        $mmHg->addAlias('millimeters of mercury');
        $this->addUnit($mmHg);

        // PSI (pounds per square inch)
        $psi = new UnitOfMeasurement('psi', 6894.757293168);
        $psi->addAlias('PSI');
        $psi->addAlias('pound per square inch');
        $psi->addAlias('pounds per square inch');
        $psi->addAlias('lbf/in²');
        $this->addUnit($psi);

        // PSF (pounds per square foot)
        $psf = new UnitOfMeasurement('psf', 47.880258980336);
        $psf->addAlias('PSF');
        $psf->addAlias('pound per square foot');
        $psf->addAlias('pounds per square foot');
        $psf->addAlias('lbf/ft²');
        $this->addUnit($psf);

        // Inches of mercury
        $inchHg = new UnitOfMeasurement('inHg', 3386.389);
        $inchHg->addAlias('inch of mercury');
        $inchHg->addAlias('inches of mercury');
        $this->addUnit($inchHg);

        // Technical atmosphere
        $technicalAtmosphere = new UnitOfMeasurement('at', 98066.5);
        $technicalAtmosphere->addAlias('technical atmosphere');
        $this->addUnit($technicalAtmosphere);
    }
}

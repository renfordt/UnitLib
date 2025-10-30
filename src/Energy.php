<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

class Energy extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'J';
    }

    /**
     * Create Energy from Force × Length (Work = Force × Distance).
     */
    public static function fromForceAndLength(Force $force, Length $length): self
    {
        /** @var self */
        return $force->multiply($length);
    }

    protected function initialise(): void
    {
        // SI base unit (Joule)
        $joule = new UnitOfMeasurement('J', 1);
        $joule->addAlias('joule');
        $joule->addAlias('joules');
        $this->addUnit($joule);

        // Watt-hour units
        $wattHour = new UnitOfMeasurement('Wh', 3600);
        $wattHour->addAlias('watt-hour');
        $wattHour->addAlias('watt-hours');
        $this->addUnit($wattHour);

        $kilowattHour = new UnitOfMeasurement('kWh', 3600000);
        $kilowattHour->addAlias('kilowatt-hour');
        $kilowattHour->addAlias('kilowatt-hours');
        $this->addUnit($kilowattHour);

        // Calorie units
        $calorie = new UnitOfMeasurement('cal', 4.184);
        $calorie->addAlias('calorie');
        $calorie->addAlias('calories');
        $this->addUnit($calorie);

        $kilocalorie = new UnitOfMeasurement('kcal', 4184);
        $kilocalorie->addAlias('Cal');
        $kilocalorie->addAlias('kilocalorie');
        $kilocalorie->addAlias('kilocalories');
        $kilocalorie->addAlias('Calorie');
        $kilocalorie->addAlias('Calories');
        $this->addUnit($kilocalorie);

        // BTU (British Thermal Unit)
        $btu = new UnitOfMeasurement('BTU', 1055.05585262);
        $btu->addAlias('btu');
        $btu->addAlias('British thermal unit');
        $this->addUnit($btu);

        // Electronvolt
        $electronvolt = new UnitOfMeasurement('eV', 1.602176634e-19);
        $electronvolt->addAlias('electronvolt');
        $electronvolt->addAlias('electron volt');
        $this->addUnit($electronvolt);

        // Erg (CGS unit)
        $erg = new UnitOfMeasurement('erg', 1e-7);
        $erg->addAlias('ergs');
        $this->addUnit($erg);

        // Foot-pound
        $footPound = new UnitOfMeasurement('ft·lbf', 1.3558179483314004);
        $footPound->addAlias('ft-lb');
        $footPound->addAlias('foot-pound');
        $footPound->addAlias('foot-pounds');
        $this->addUnit($footPound);
    }
}

<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

class Power extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'W';
    }

    /**
     * Create Power from Energy / Time (Power = Energy per unit time).
     */
    public static function fromEnergyAndTime(Energy $energy, Time $time): self
    {
        /** @var self */
        return $energy->divide($time);
    }

    protected function initialise(): void
    {
        // SI base unit (Watt)
        $watt = new UnitOfMeasurement('W', 1);
        $watt->addAlias('watt');
        $watt->addAlias('watts');
        $this->addUnit($watt);

        // Horsepower variants
        $horsepowerMechanical = new UnitOfMeasurement('hp', 745.69987158227022);
        $horsepowerMechanical->addAlias('HP');
        $horsepowerMechanical->addAlias('horsepower');
        $horsepowerMechanical->addAlias('mechanical horsepower');
        $this->addUnit($horsepowerMechanical);

        $horsepowerMetric = new UnitOfMeasurement('PS', 735.49875);
        $horsepowerMetric->addAlias('ps');
        $horsepowerMetric->addAlias('metric horsepower');
        $horsepowerMetric->addAlias('pferdestärke');
        $this->addUnit($horsepowerMetric);

        $horsepowerElectric = new UnitOfMeasurement('hp(E)', 746);
        $horsepowerElectric->addAlias('electric horsepower');
        $this->addUnit($horsepowerElectric);

        // BTU per hour
        $btuPerHour = new UnitOfMeasurement('BTU/h', 0.29307107017);
        $btuPerHour->addAlias('BTU per hour');
        $this->addUnit($btuPerHour);

        // Calorie per second
        $caloriePerSecond = new UnitOfMeasurement('cal/s', 4.184);
        $caloriePerSecond->addAlias('calorie per second');
        $this->addUnit($caloriePerSecond);

        // Foot-pound per second
        $footPoundPerSecond = new UnitOfMeasurement('ft·lbf/s', 1.3558179483314004);
        $footPoundPerSecond->addAlias('ft-lb/s');
        $footPoundPerSecond->addAlias('foot-pound per second');
        $this->addUnit($footPoundPerSecond);
    }
}

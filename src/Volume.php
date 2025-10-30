<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

class Volume extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'm³';
    }

    /**
     * Create a Volume from Length cubed (Length³).
     */
    public static function fromLength(Length $length): self
    {
        /** @var self */
        return $length->power(3);
    }

    /**
     * Create a Volume from Area × Length.
     */
    public static function fromAreaAndLength(Area $area, Length $length): self
    {
        /** @var self */
        return $area->multiply($length);
    }

    /**
     * Create a Volume from three Length dimensions (e.g., width × height × depth).
     */
    public static function fromLengths(Length $length1, Length $length2, Length $length3): self
    {
        $area = $length1->multiply($length2);

        /** @var self */
        return $area->multiply($length3);
    }

    protected function initialise(): void
    {
        // Metric base unit (SI)
        $cubicMeter = new UnitOfMeasurement('m³', 1);
        $cubicMeter->addAlias('m3');
        $cubicMeter->addAlias('cubic meter');
        $cubicMeter->addAlias('cubic meters');
        $cubicMeter->addAlias('cubic metre');
        $cubicMeter->addAlias('cubic metres');
        $this->addUnit($cubicMeter);

        // Metric volume units
        $liter = new UnitOfMeasurement('L', 0.001);
        $liter->addAlias('l');
        $liter->addAlias('liter');
        $liter->addAlias('liters');
        $liter->addAlias('litre');
        $liter->addAlias('litres');
        $this->addUnit($liter);

        $milliliter = new UnitOfMeasurement('mL', 0.000001);
        $milliliter->addAlias('ml');
        $milliliter->addAlias('milliliter');
        $milliliter->addAlias('milliliters');
        $milliliter->addAlias('millilitre');
        $milliliter->addAlias('millilitres');
        $this->addUnit($milliliter);

        // Imperial units - cubic
        $cubicInch = new UnitOfMeasurement('in³', 0.000016387064);
        $cubicInch->addAlias('in3');
        $cubicInch->addAlias('cubic inch');
        $cubicInch->addAlias('cubic inches');
        $this->addUnit($cubicInch);

        $cubicFoot = new UnitOfMeasurement('ft³', 0.028316846592);
        $cubicFoot->addAlias('ft3');
        $cubicFoot->addAlias('cubic foot');
        $cubicFoot->addAlias('cubic feet');
        $this->addUnit($cubicFoot);

        $cubicYard = new UnitOfMeasurement('yd³', 0.764554857984);
        $cubicYard->addAlias('yd3');
        $cubicYard->addAlias('cubic yard');
        $cubicYard->addAlias('cubic yards');
        $this->addUnit($cubicYard);

        // US liquid units
        $fluidOunce = new UnitOfMeasurement('fl oz', 0.0000295735295625);
        $fluidOunce->addAlias('fluid ounce');
        $fluidOunce->addAlias('fluid ounces');
        $this->addUnit($fluidOunce);

        $cup = new UnitOfMeasurement('cup', 0.0002365882365);
        $cup->addAlias('cups');
        $this->addUnit($cup);

        $pint = new UnitOfMeasurement('pt', 0.000473176473);
        $pint->addAlias('pint');
        $pint->addAlias('pints');
        $this->addUnit($pint);

        $quart = new UnitOfMeasurement('qt', 0.000946352946);
        $quart->addAlias('quart');
        $quart->addAlias('quarts');
        $this->addUnit($quart);

        $gallon = new UnitOfMeasurement('gal', 0.003785411784);
        $gallon->addAlias('gallon');
        $gallon->addAlias('gallons');
        $this->addUnit($gallon);

        // UK imperial units
        $imperialFluidOunce = new UnitOfMeasurement('imp fl oz', 0.0000284130625);
        $imperialFluidOunce->addAlias('imperial fluid ounce');
        $imperialFluidOunce->addAlias('imperial fluid ounces');
        $this->addUnit($imperialFluidOunce);

        $imperialPint = new UnitOfMeasurement('imp pt', 0.00056826125);
        $imperialPint->addAlias('imperial pint');
        $imperialPint->addAlias('imperial pints');
        $this->addUnit($imperialPint);

        $imperialQuart = new UnitOfMeasurement('imp qt', 0.0011365225);
        $imperialQuart->addAlias('imperial quart');
        $imperialQuart->addAlias('imperial quarts');
        $this->addUnit($imperialQuart);

        $imperialGallon = new UnitOfMeasurement('imp gal', 0.00454609);
        $imperialGallon->addAlias('imperial gallon');
        $imperialGallon->addAlias('imperial gallons');
        $this->addUnit($imperialGallon);
    }
}

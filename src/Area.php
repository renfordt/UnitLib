<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

class Area extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'm²';
    }

    /**
     * Create an Area from Length squared (Length × Length).
     */
    public static function fromLength(Length $length): self
    {
        /** @var self */
        return $length->power(2);
    }

    /**
     * Create an Area from two Length dimensions (e.g., width × height).
     */
    public static function fromLengths(Length $length1, Length $length2): self
    {
        /** @var self */
        return $length1->multiply($length2);
    }

    protected function initialise(): void
    {
        // Metric base unit (SI)
        $squareMeter = new UnitOfMeasurement('m²', 1);
        $squareMeter->addAlias('m2');
        $squareMeter->addAlias('square meter');
        $squareMeter->addAlias('square meters');
        $squareMeter->addAlias('square metre');
        $squareMeter->addAlias('square metres');
        $this->addUnit($squareMeter);

        // Metric derived units
        $are = new UnitOfMeasurement('a', 100);
        $are->addAlias('are');
        $are->addAlias('ares');
        $this->addUnit($are);

        $hectare = new UnitOfMeasurement('ha', 10000);
        $hectare->addAlias('hectare');
        $hectare->addAlias('hectares');
        $this->addUnit($hectare);

        // Imperial units
        $squareInch = new UnitOfMeasurement('in²', 0.00064516);
        $squareInch->addAlias('in2');
        $squareInch->addAlias('square inch');
        $squareInch->addAlias('square inches');
        $this->addUnit($squareInch);

        $squareFoot = new UnitOfMeasurement('ft²', 0.09290304);
        $squareFoot->addAlias('ft2');
        $squareFoot->addAlias('square foot');
        $squareFoot->addAlias('square feet');
        $this->addUnit($squareFoot);

        $squareYard = new UnitOfMeasurement('yd²', 0.83612736);
        $squareYard->addAlias('yd2');
        $squareYard->addAlias('square yard');
        $squareYard->addAlias('square yards');
        $this->addUnit($squareYard);

        $acre = new UnitOfMeasurement('ac', 4046.8564224);
        $acre->addAlias('acre');
        $acre->addAlias('acres');
        $this->addUnit($acre);

        $squareMile = new UnitOfMeasurement('mi²', 2589988.110336);
        $squareMile->addAlias('mi2');
        $squareMile->addAlias('square mile');
        $squareMile->addAlias('square miles');
        $this->addUnit($squareMile);
    }
}

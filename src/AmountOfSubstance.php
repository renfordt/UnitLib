<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Class AmountOfSubstance
 *
 * Represents an amount of substance measurement (number of particles/molecules).
 * Native unit: mole (mol)
 *
 * The mole is one of the seven SI base units and is defined as exactly
 * 6.02214076×10²³ elementary entities (Avogadro's number).
 *
 * Supported units:
 * - Mole (mol) and metric prefixes (kmol, mmol, μmol, nmol, etc.)
 *
 * @package Renfordt\UnitLib
 */
class AmountOfSubstance extends PhysicalQuantity
{
    use HasSIUnits;

    protected function getSIBaseUnit(): string
    {
        return 'mol';
    }

    protected function initialise(): void
    {
        // Mole is the native unit
        $mole = new UnitOfMeasurement('mol', 1);
        $mole->addAlias('mole');
        $mole->addAlias('moles');
        $this->addUnit($mole);
    }
}

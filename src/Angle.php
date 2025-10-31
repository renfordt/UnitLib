<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

/**
 * Class Angle
 *
 * Represents an angle measurement.
 * Native unit: radian (rad)
 *
 * Supported units:
 * - Radians (rad)
 * - Degrees (°, deg, degree, degrees)
 * - Arcminutes (', arcmin, arcminute, arcminutes)
 * - Arcseconds (", arcsec, arcsecond, arcseconds)
 *
 * @package Renfordt\UnitLib
 */
class Angle extends PhysicalQuantity
{
    protected function initialise(): void
    {
        // Radian is the native unit
        $radian = new UnitOfMeasurement('rad', 1);
        $radian->addAlias('radian');
        $radian->addAlias('radians');
        $this->addUnit($radian);

        // Degree: 1° = π/180 radians
        $degree = new UnitOfMeasurement('°', M_PI / 180);
        $degree->addAlias('deg');
        $degree->addAlias('degree');
        $degree->addAlias('degrees');
        $this->addUnit($degree);

        // Arcminute: 1' = 1/60 degree = π/10800 radians
        $arcminute = new UnitOfMeasurement('\'', M_PI / 10800);
        $arcminute->addAlias('arcmin');
        $arcminute->addAlias('arcminute');
        $arcminute->addAlias('arcminutes');
        $this->addUnit($arcminute);

        // Arcsecond: 1" = 1/60 arcminute = 1/3600 degree = π/648000 radians
        $arcsecond = new UnitOfMeasurement('"', M_PI / 648000);
        $arcsecond->addAlias('arcsec');
        $arcsecond->addAlias('arcsecond');
        $arcsecond->addAlias('arcseconds');
        $this->addUnit($arcsecond);
    }

    /**
     * Normalize angle to [0, 2π) range in radians
     */
    public function normalize(): self
    {
        $normalizedValue = fmod($this->nativeValue, 2 * M_PI);
        if ($normalizedValue < 0) {
            $normalizedValue += 2 * M_PI;
        }

        return new self($normalizedValue, $this->nativeUnit);
    }

    /**
     * Convert angle to [0, 360°) range
     */
    public function normalizeDegrees(): self
    {
        $degrees = $this->toUnit('°');
        $normalizedDegrees = fmod($degrees, 360);
        if ($normalizedDegrees < 0) {
            $normalizedDegrees += 360;
        }

        return new self($normalizedDegrees, '°');
    }

    /**
     * Get the complementary angle (90° - angle)
     */
    public function complement(): self
    {
        $rightAngle = new self(M_PI / 2, 'rad');
        /** @var self */
        return $rightAngle->subtract($this);
    }

    /**
     * Get the supplementary angle (180° - angle)
     */
    public function supplement(): self
    {
        $straightAngle = new self(M_PI, 'rad');
        /** @var self */
        return $straightAngle->subtract($this);
    }
}

<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Angle;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(Angle::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
class AngleTest extends TestCase
{
    public function test_can_create_angle_in_radians(): void
    {
        $angle = new Angle(M_PI, 'rad');
        self::assertSame(M_PI, $angle->toUnit('rad'));
    }

    public function test_can_create_angle_in_degrees(): void
    {
        $angle = new Angle(180, '°');
        self::assertEqualsWithDelta(180, $angle->toUnit('°'), 0.0001);
    }

    public function test_can_convert_degrees_to_radians(): void
    {
        $angle = new Angle(180, '°');
        self::assertEqualsWithDelta(M_PI, $angle->toUnit('rad'), 0.0001);
    }

    public function test_can_convert_radians_to_degrees(): void
    {
        $angle = new Angle(M_PI, 'rad');
        self::assertEqualsWithDelta(180, $angle->toUnit('°'), 0.0001);
    }

    public function test_can_create_angle_in_arcminutes(): void
    {
        $angle = new Angle(60, 'arcmin');
        self::assertEqualsWithDelta(60, $angle->toUnit('arcmin'), 0.0001);
    }

    public function test_can_convert_arcminutes_to_degrees(): void
    {
        $angle = new Angle(60, 'arcmin');
        self::assertEqualsWithDelta(1, $angle->toUnit('°'), 0.0001);
    }

    public function test_can_create_angle_in_arcseconds(): void
    {
        $angle = new Angle(3600, 'arcsec');
        self::assertEqualsWithDelta(3600, $angle->toUnit('arcsec'), 0.0001);
    }

    public function test_can_convert_arcseconds_to_degrees(): void
    {
        $angle = new Angle(3600, 'arcsec');
        self::assertEqualsWithDelta(1, $angle->toUnit('°'), 0.0001);
    }

    public function test_can_convert_arcseconds_to_arcminutes(): void
    {
        $angle = new Angle(60, 'arcsec');
        self::assertEqualsWithDelta(1, $angle->toUnit('arcmin'), 0.0001);
    }

    public function test_can_convert_degrees_to_arcminutes(): void
    {
        $angle = new Angle(1, '°');
        self::assertEqualsWithDelta(60, $angle->toUnit('arcmin'), 0.0001);
    }

    public function test_can_convert_degrees_to_arcseconds(): void
    {
        $angle = new Angle(1, '°');
        self::assertEqualsWithDelta(3600, $angle->toUnit('arcsec'), 0.0001);
    }

    public function test_degree_aliases_work(): void
    {
        $angle1 = new Angle(90, 'deg');
        $angle2 = new Angle(90, 'degree');
        $angle3 = new Angle(90, 'degrees');

        self::assertEqualsWithDelta($angle1->toUnit('rad'), $angle2->toUnit('rad'), 0.0001);
        self::assertEqualsWithDelta($angle1->toUnit('rad'), $angle3->toUnit('rad'), 0.0001);
    }

    public function test_radian_aliases_work(): void
    {
        $angle1 = new Angle(1, 'rad');
        $angle2 = new Angle(1, 'radian');
        $angle3 = new Angle(1, 'radians');

        self::assertSame($angle1->toUnit('rad'), $angle2->toUnit('rad'));
        self::assertSame($angle1->toUnit('rad'), $angle3->toUnit('rad'));
    }

    public function test_arcminute_symbol_alias_works(): void
    {
        $angle1 = new Angle(30, '\'');
        $angle2 = new Angle(30, 'arcmin');

        self::assertEqualsWithDelta($angle1->toUnit('rad'), $angle2->toUnit('rad'), 0.0001);
    }

    public function test_arcsecond_symbol_alias_works(): void
    {
        $angle1 = new Angle(120, '"');
        $angle2 = new Angle(120, 'arcsec');

        self::assertEqualsWithDelta($angle1->toUnit('rad'), $angle2->toUnit('rad'), 0.0001);
    }

    public function test_can_add_angles(): void
    {
        $angle1 = new Angle(45, '°');
        $angle2 = new Angle(45, '°');
        $result = $angle1->add($angle2);

        self::assertEqualsWithDelta(90, $result->toUnit('°'), 0.0001);
    }

    public function test_can_subtract_angles(): void
    {
        $angle1 = new Angle(90, '°');
        $angle2 = new Angle(30, '°');
        $result = $angle1->subtract($angle2);

        self::assertEqualsWithDelta(60, $result->toUnit('°'), 0.0001);
    }

    public function test_normalize_positive_angle(): void
    {
        $angle = new Angle(45, '°');
        $normalized = $angle->normalize();

        self::assertEqualsWithDelta(45, $normalized->toUnit('°'), 0.0001);
    }

    public function test_normalize_angle_over_360(): void
    {
        $angle = new Angle(450, '°');
        $normalized = $angle->normalize();

        self::assertEqualsWithDelta(90, $normalized->toUnit('°'), 0.0001);
    }

    public function test_normalize_negative_angle(): void
    {
        $angle = new Angle(-30, '°');
        $normalized = $angle->normalize();

        self::assertEqualsWithDelta(330, $normalized->toUnit('°'), 0.0001);
    }

    public function test_normalize_degrees_positive_angle(): void
    {
        $angle = new Angle(M_PI / 4, 'rad');
        $normalized = $angle->normalizeDegrees();

        self::assertEqualsWithDelta(45, $normalized->toUnit('°'), 0.0001);
    }

    public function test_normalize_degrees_over_360(): void
    {
        $angle = new Angle(450, '°');
        $normalized = $angle->normalizeDegrees();

        self::assertEqualsWithDelta(90, $normalized->toUnit('°'), 0.0001);
    }

    public function test_normalize_degrees_negative_angle(): void
    {
        $angle = new Angle(-30, '°');
        $normalized = $angle->normalizeDegrees();

        self::assertEqualsWithDelta(330, $normalized->toUnit('°'), 0.0001);
    }

    public function test_complement_of_angle(): void
    {
        $angle = new Angle(30, '°');
        $complement = $angle->complement();

        self::assertEqualsWithDelta(60, $complement->toUnit('°'), 0.0001);
    }

    public function test_complement_of_45_degrees(): void
    {
        $angle = new Angle(45, '°');
        $complement = $angle->complement();

        self::assertEqualsWithDelta(45, $complement->toUnit('°'), 0.0001);
    }

    public function test_supplement_of_angle(): void
    {
        $angle = new Angle(30, '°');
        $supplement = $angle->supplement();

        self::assertEqualsWithDelta(150, $supplement->toUnit('°'), 0.0001);
    }

    public function test_supplement_of_90_degrees(): void
    {
        $angle = new Angle(90, '°');
        $supplement = $angle->supplement();

        self::assertEqualsWithDelta(90, $supplement->toUnit('°'), 0.0001);
    }

    public function test_string_representation(): void
    {
        $angle = new Angle(90, '°');
        self::assertSame('90 °', (string) $angle);
    }

    public function test_string_representation_with_radians(): void
    {
        $angle = new Angle(M_PI, 'rad');
        $expected = M_PI . ' rad';
        self::assertSame($expected, (string) $angle);
    }

    public function test_common_angle_conversions(): void
    {
        // 90° = π/2 rad = 5400 arcmin = 324000 arcsec
        $angle = new Angle(90, '°');

        self::assertEqualsWithDelta(M_PI / 2, $angle->toUnit('rad'), 0.0001);
        self::assertEqualsWithDelta(5400, $angle->toUnit('arcmin'), 0.0001);
        self::assertEqualsWithDelta(324000, $angle->toUnit('arcsec'), 0.0001);
    }

    public function test_full_circle_in_radians(): void
    {
        $angle = new Angle(2 * M_PI, 'rad');
        self::assertEqualsWithDelta(360, $angle->toUnit('°'), 0.0001);
    }

    public function test_right_angle(): void
    {
        $angle = new Angle(M_PI / 2, 'rad');
        self::assertEqualsWithDelta(90, $angle->toUnit('°'), 0.0001);
    }

    public function test_straight_angle(): void
    {
        $angle = new Angle(M_PI, 'rad');
        self::assertEqualsWithDelta(180, $angle->toUnit('°'), 0.0001);
    }
}

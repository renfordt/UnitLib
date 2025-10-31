<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\AmountOfSubstance;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(AmountOfSubstance::class)]
#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(UnitOfMeasurement::class)]
class AmountOfSubstanceTest extends TestCase
{
    public function test_can_create_amount_in_moles(): void
    {
        $amount = new AmountOfSubstance(1, 'mol');
        self::assertSame(1.0, $amount->toUnit('mol'));
    }

    public function test_can_create_amount_with_aliases(): void
    {
        $amount1 = new AmountOfSubstance(2, 'mole');
        $amount2 = new AmountOfSubstance(2, 'moles');

        self::assertSame(2.0, $amount1->toUnit('mol'));
        self::assertSame(2.0, $amount2->toUnit('mol'));
    }

    public function test_can_convert_to_kilomoles(): void
    {
        $amount = new AmountOfSubstance(1000, 'mol');
        self::assertSame(1.0, $amount->toUnit('kmol'));
    }

    public function test_can_convert_to_millimoles(): void
    {
        $amount = new AmountOfSubstance(1, 'mol');
        self::assertSame(1000.0, $amount->toUnit('mmol'));
    }

    public function test_can_convert_to_micromoles(): void
    {
        $amount = new AmountOfSubstance(1, 'mol');
        self::assertSame(1_000_000.0, $amount->toUnit('μmol'));
    }

    public function test_can_convert_to_nanomoles(): void
    {
        $amount = new AmountOfSubstance(1, 'mol');
        self::assertSame(1_000_000_000.0, $amount->toUnit('nmol'));
    }

    public function test_can_convert_from_millimoles_to_moles(): void
    {
        $amount = new AmountOfSubstance(500, 'mmol');
        self::assertSame(0.5, $amount->toUnit('mol'));
    }

    public function test_can_convert_from_micromoles_to_millimoles(): void
    {
        $amount = new AmountOfSubstance(1000, 'μmol');
        self::assertSame(1.0, $amount->toUnit('mmol'));
    }

    public function test_can_add_amounts(): void
    {
        $amount1 = new AmountOfSubstance(1, 'mol');
        $amount2 = new AmountOfSubstance(2, 'mol');
        $result = $amount1->add($amount2);

        self::assertSame(3.0, $result->toUnit('mol'));
    }

    public function test_can_add_amounts_with_different_units(): void
    {
        $amount1 = new AmountOfSubstance(1, 'mol');
        $amount2 = new AmountOfSubstance(500, 'mmol');
        $result = $amount1->add($amount2);

        self::assertSame(1.5, $result->toUnit('mol'));
    }

    public function test_can_subtract_amounts(): void
    {
        $amount1 = new AmountOfSubstance(5, 'mol');
        $amount2 = new AmountOfSubstance(2, 'mol');
        $result = $amount1->subtract($amount2);

        self::assertSame(3.0, $result->toUnit('mol'));
    }

    public function test_can_subtract_amounts_with_different_units(): void
    {
        $amount1 = new AmountOfSubstance(1, 'mol');
        $amount2 = new AmountOfSubstance(250, 'mmol');
        $result = $amount1->subtract($amount2);

        self::assertSame(0.75, $result->toUnit('mol'));
    }

    public function test_string_representation(): void
    {
        $amount = new AmountOfSubstance(2.5, 'mol');
        self::assertSame('2.5 mol', (string) $amount);
    }

    public function test_string_representation_with_prefix(): void
    {
        $amount = new AmountOfSubstance(500, 'mmol');
        self::assertSame('500 mmol', (string) $amount);
    }

    public function test_avogadros_number_example(): void
    {
        // 1 mole = 6.02214076×10²³ entities (Avogadro's number)
        // This test just verifies the unit works, not the actual constant value
        $amount = new AmountOfSubstance(1, 'mol');
        self::assertSame(1.0, $amount->toUnit('mol'));
    }

    public function test_chemistry_example_millimoles(): void
    {
        // Common chemistry: 250 mmol + 750 mmol = 1 mol
        $amount1 = new AmountOfSubstance(250, 'mmol');
        $amount2 = new AmountOfSubstance(750, 'mmol');
        $result = $amount1->add($amount2);

        self::assertSame(1.0, $result->toUnit('mol'));
    }

    public function test_all_si_prefixes_work(): void
    {
        $amount = new AmountOfSubstance(1, 'mol');

        // Test various SI prefixes
        self::assertSame(1000.0, $amount->toUnit('mmol'));
        self::assertSame(1_000_000.0, $amount->toUnit('μmol'));
        self::assertSame(0.001, $amount->toUnit('kmol'));
        self::assertSame(100.0, $amount->toUnit('cmol'));
        self::assertSame(10.0, $amount->toUnit('dmol'));
    }

    public function test_can_convert_between_different_prefixes(): void
    {
        $amount = new AmountOfSubstance(1, 'kmol');
        self::assertSame(1_000_000.0, $amount->toUnit('mmol'));
    }

    public function test_immutability_after_operations(): void
    {
        $original = new AmountOfSubstance(5, 'mol');
        $result = $original->add(new AmountOfSubstance(3, 'mol'));

        self::assertSame(5.0, $original->toUnit('mol'));
        self::assertSame(8.0, $result->toUnit('mol'));
    }
}

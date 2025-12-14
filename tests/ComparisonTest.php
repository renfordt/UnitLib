<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Length;
use Renfordt\UnitLib\Mass;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\Temperature;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(Length::class)]
#[CoversClass(Mass::class)]
#[CoversClass(Temperature::class)]
#[CoversClass(UnitOfMeasurement::class)]
final class ComparisonTest extends TestCase
{
    public function test_compare_to_returns_positive_when_greater(): void
    {
        $length1 = new Length(10, 'm');
        $length2 = new Length(5, 'm');

        $this->assertGreaterThan(0, $length1->compareTo($length2));
    }

    public function test_compare_to_returns_negative_when_less(): void
    {
        $length1 = new Length(5, 'm');
        $length2 = new Length(10, 'm');

        $this->assertLessThan(0, $length1->compareTo($length2));
    }

    public function test_compare_to_returns_zero_when_equal(): void
    {
        $length1 = new Length(10, 'm');
        $length2 = new Length(10, 'm');

        $this->assertEquals(0, $length1->compareTo($length2));
    }

    public function test_compare_to_works_with_different_units(): void
    {
        $length1 = new Length(1, 'km');
        $length2 = new Length(500, 'm');

        $this->assertGreaterThan(0, $length1->compareTo($length2));
    }

    public function test_greater_than_returns_true_when_greater(): void
    {
        $length1 = new Length(10, 'm');
        $length2 = new Length(5, 'm');

        $this->assertTrue($length1->greaterThan($length2));
    }

    public function test_greater_than_returns_false_when_not_greater(): void
    {
        $length1 = new Length(5, 'm');
        $length2 = new Length(10, 'm');

        $this->assertFalse($length1->greaterThan($length2));
        $this->assertFalse($length1->greaterThan($length1));
    }

    public function test_less_than_returns_true_when_less(): void
    {
        $length1 = new Length(5, 'm');
        $length2 = new Length(10, 'm');

        $this->assertTrue($length1->lessThan($length2));
    }

    public function test_less_than_returns_false_when_not_less(): void
    {
        $length1 = new Length(10, 'm');
        $length2 = new Length(5, 'm');

        $this->assertFalse($length1->lessThan($length2));
        $this->assertFalse($length1->lessThan($length1));
    }

    public function test_greater_than_or_equal_to_works_correctly(): void
    {
        $length1 = new Length(10, 'm');
        $length2 = new Length(5, 'm');
        $length3 = new Length(10, 'm');

        $this->assertTrue($length1->greaterThanOrEqualTo($length2));
        $this->assertTrue($length1->greaterThanOrEqualTo($length3));
        $this->assertFalse($length2->greaterThanOrEqualTo($length1));
    }

    public function test_less_than_or_equal_to_works_correctly(): void
    {
        $length1 = new Length(5, 'm');
        $length2 = new Length(10, 'm');
        $length3 = new Length(5, 'm');

        $this->assertTrue($length1->lessThanOrEqualTo($length2));
        $this->assertTrue($length1->lessThanOrEqualTo($length3));
        $this->assertFalse($length2->lessThanOrEqualTo($length1));
    }

    public function test_equals_returns_true_for_same_values(): void
    {
        $length1 = new Length(10, 'm');
        $length2 = new Length(10, 'm');

        $this->assertTrue($length1->equals($length2));
    }

    public function test_equals_returns_false_for_different_values(): void
    {
        $length1 = new Length(10, 'm');
        $length2 = new Length(5, 'm');

        $this->assertFalse($length1->equals($length2));
    }

    public function test_equals_works_with_different_units(): void
    {
        $length1 = new Length(1000, 'mm');
        $length2 = new Length(1, 'm');

        $this->assertTrue($length1->equals($length2));
    }

    public function test_equals_uses_epsilon_for_floating_point(): void
    {
        $length1 = new Length(10.00000000001, 'm');
        $length2 = new Length(10, 'm');

        // Should be equal with default epsilon (1e-10)
        $this->assertTrue($length1->equals($length2));

        // Should not be equal with very small epsilon
        $this->assertFalse($length1->equals($length2, 1e-15));
    }

    public function test_equals_with_custom_epsilon(): void
    {
        $length1 = new Length(10.01, 'm');
        $length2 = new Length(10, 'm');

        // Should not be equal with default epsilon
        $this->assertFalse($length1->equals($length2));

        // Should be equal with larger epsilon
        $this->assertTrue($length1->equals($length2, 0.02));
    }

    public function test_compare_to_throws_exception_for_different_types(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot compare');

        $length = new Length(10, 'm');
        $mass = new Mass(10, 'kg');

        $length->compareTo($mass);
    }

    public function test_equals_throws_exception_for_different_types(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot compare');

        $length = new Length(10, 'm');
        $mass = new Mass(10, 'kg');

        $length->equals($mass);
    }

    public function test_comparisons_work_with_temperature(): void
    {
        $temp1 = new Temperature(100, '°C');
        $temp2 = new Temperature(212, '°F');
        $temp3 = new Temperature(50, '°C');

        // 100°C = 212°F, should be equal
        $this->assertTrue($temp1->equals($temp2, 0.01));
        $this->assertEquals(0, $temp1->compareTo($temp2));

        // 100°C > 50°C
        $this->assertTrue($temp1->greaterThan($temp3));
        $this->assertTrue($temp3->lessThan($temp1));
    }

    public function test_comparisons_work_across_si_prefixes(): void
    {
        $length1 = new Length(1, 'km');
        $length2 = new Length(999, 'm');
        $length3 = new Length(1000, 'm');

        $this->assertTrue($length1->greaterThan($length2));
        $this->assertTrue($length1->equals($length3));
        $this->assertTrue($length2->lessThan($length3));
    }

    public function test_comparison_chain_for_sorting(): void
    {
        $lengths = [
            new Length(5, 'm'),
            new Length(200, 'cm'),
            new Length(3000, 'mm'),
            new Length(0.001, 'km'),
        ];

        // Sort using compareTo
        usort($lengths, fn ($a, \Renfordt\UnitLib\PhysicalQuantity $b): int => $a->compareTo($b));

        $this->assertEqualsWithDelta(1, $lengths[0]->toUnit('m'), 0.001);
        $this->assertEqualsWithDelta(2, $lengths[1]->toUnit('m'), 0.001);
        $this->assertEqualsWithDelta(3, $lengths[2]->toUnit('m'), 0.001);
        $this->assertEqualsWithDelta(5, $lengths[3]->toUnit('m'), 0.001);
    }
}

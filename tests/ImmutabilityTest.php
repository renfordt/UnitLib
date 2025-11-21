<?php

declare(strict_types=1);

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\Area;
use Renfordt\UnitLib\Energy;
use Renfordt\UnitLib\Force;
use Renfordt\UnitLib\Length;
use Renfordt\UnitLib\Mass;
use Renfordt\UnitLib\PhysicalQuantity;
use Renfordt\UnitLib\Power;
use Renfordt\UnitLib\Pressure;
use Renfordt\UnitLib\Temperature;
use Renfordt\UnitLib\Time;
use Renfordt\UnitLib\UnitOfMeasurement;
use Renfordt\UnitLib\Volume;

#[CoversClass(PhysicalQuantity::class)]
#[CoversClass(Area::class)]
#[CoversClass(Energy::class)]
#[CoversClass(Force::class)]
#[CoversClass(Length::class)]
#[CoversClass(Mass::class)]
#[CoversClass(Power::class)]
#[CoversClass(Pressure::class)]
#[CoversClass(Temperature::class)]
#[CoversClass(Time::class)]
#[CoversClass(Volume::class)]
#[CoversClass(UnitOfMeasurement::class)]
class ImmutabilityTest extends TestCase
{
    /**
     * Test that originalValue property is readonly and cannot be modified.
     */
    public function test_original_value_is_readonly(): void
    {
        $length = new Length(10, 'm');

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Cannot modify readonly property');
        $length->originalValue = 20;
    }

    /**
     * Test that nativeValue property is readonly and cannot be modified.
     */
    public function test_native_value_is_readonly(): void
    {
        $length = new Length(10, 'm');

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Cannot modify readonly property');
        $length->nativeValue = 20;
    }

    /**
     * Test that nativeUnit property is readonly and cannot be modified.
     */
    public function test_native_unit_is_readonly(): void
    {
        $length = new Length(10, 'm');
        $newUnit = new UnitOfMeasurement('km', 1000);

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Cannot modify readonly property');
        $length->nativeUnit = $newUnit;
    }

    /**
     * Test that add() returns a new instance and doesn't modify the original.
     */
    public function test_add_returns_new_instance(): void
    {
        $length1 = new Length(10, 'm');
        $length2 = new Length(5, 'm');

        $result = $length1->add($length2);

        // Result should be a new instance
        $this->assertNotSame($length1, $result);
        $this->assertNotSame($length2, $result);

        // Original values should remain unchanged
        $this->assertSame(10.0, $length1->originalValue);
        $this->assertSame(5.0, $length2->originalValue);

        // Result should have the sum
        $this->assertSame(15.0, $result->nativeValue);
    }

    /**
     * Test that subtract() returns a new instance and doesn't modify the original.
     */
    public function test_subtract_returns_new_instance(): void
    {
        $length1 = new Length(10, 'm');
        $length2 = new Length(5, 'm');

        $result = $length1->subtract($length2);

        // Result should be a new instance
        $this->assertNotSame($length1, $result);
        $this->assertNotSame($length2, $result);

        // Original values should remain unchanged
        $this->assertSame(10.0, $length1->originalValue);
        $this->assertSame(5.0, $length2->originalValue);

        // Result should have the difference
        $this->assertSame(5.0, $result->nativeValue);
    }

    /**
     * Test that toNativeUnit() returns a new instance.
     */
    public function test_to_native_unit_returns_new_instance(): void
    {
        $length = new Length(1000, 'mm');

        $result = $length->toNativeUnit();

        // Result should be a new instance
        $this->assertNotSame($length, $result);

        // Original should remain unchanged
        $this->assertSame(1000.0, $length->originalValue);
        $this->assertSame(1.0, $length->nativeValue); // 1000mm = 1m

        // Result should be in native units
        $this->assertSame(1.0, $result->originalValue);
        $this->assertSame('m', $result->nativeUnit->name);
    }

    /**
     * Test that a Length always remains a Length.
     */
    public function test_length_remains_length(): void
    {
        $length = new Length(10, 'm');

        $this->assertInstanceOf(Length::class, $length);
        $this->assertInstanceOf(Length::class, $length->add(new Length(5, 'm')));
        $this->assertInstanceOf(Length::class, $length->subtract(new Length(5, 'm')));
        $this->assertInstanceOf(Length::class, $length->toNativeUnit());
    }

    /**
     * Test that an Area always remains an Area.
     */
    public function test_area_remains_area(): void
    {
        $area = new Area(10, 'm²');

        $this->assertInstanceOf(Area::class, $area);
        $this->assertInstanceOf(Area::class, $area->add(new Area(5, 'm²')));
        $this->assertInstanceOf(Area::class, $area->subtract(new Area(5, 'm²')));
        $this->assertInstanceOf(Area::class, $area->toNativeUnit());
    }

    /**
     * Test that a Mass always remains a Mass.
     */
    public function test_mass_remains_mass(): void
    {
        $mass = new Mass(1000, 'g');

        $this->assertInstanceOf(Mass::class, $mass);
        $this->assertInstanceOf(Mass::class, $mass->add(new Mass(500, 'g')));
        $this->assertInstanceOf(Mass::class, $mass->subtract(new Mass(500, 'g')));
        $this->assertInstanceOf(Mass::class, $mass->toNativeUnit());
    }

    /**
     * Test that a Temperature always remains a Temperature.
     */
    public function test_temperature_remains_temperature(): void
    {
        $temp = new Temperature(100, '°C');

        $this->assertInstanceOf(Temperature::class, $temp);
        $this->assertInstanceOf(Temperature::class, $temp->add(new Temperature(50, '°C')));
        $this->assertInstanceOf(Temperature::class, $temp->subtract(new Temperature(50, '°C')));
        $this->assertInstanceOf(Temperature::class, $temp->toNativeUnit());
    }

    /**
     * Test that all physical quantity types remain their type after operations.
     */
    #[DataProvider('physicalQuantityProvider')]
    public function test_physical_quantity_type_is_preserved(PhysicalQuantity $quantity, string $expectedClass): void
    {
        $this->assertInstanceOf($expectedClass, $quantity);
        $this->assertInstanceOf($expectedClass, $quantity->add($quantity));
        $this->assertInstanceOf($expectedClass, $quantity->subtract($quantity));
        $this->assertInstanceOf($expectedClass, $quantity->toNativeUnit());
    }

    /**
     * Data provider for physical quantity types.
     */
    public static function physicalQuantityProvider(): array
    {
        return [
            'Length' => [new Length(10, 'm'), Length::class],
            'Area' => [new Area(10, 'm²'), Area::class],
            'Volume' => [new Volume(10, 'L'), Volume::class],
            'Mass' => [new Mass(1000, 'g'), Mass::class],
            'Time' => [new Time(60, 's'), Time::class],
            'Temperature' => [new Temperature(25, '°C'), Temperature::class],
            'Force' => [new Force(10, 'N'), Force::class],
            'Energy' => [new Energy(100, 'J'), Energy::class],
            'Power' => [new Power(100, 'W'), Power::class],
            'Pressure' => [new Pressure(101325, 'Pa'), Pressure::class],
        ];
    }

    /**
     * Test that original values are preserved across multiple operations.
     */
    public function test_original_values_preserved_across_operations(): void
    {
        $length1 = new Length(10, 'm');
        $length2 = new Length(5, 'm');

        // Perform multiple operations
        $result1 = $length1->add($length2);
        $result2 = $length1->subtract($length2);
        $result3 = $result1->add($result2);

        // Original instances should remain unchanged
        $this->assertSame(10.0, $length1->originalValue);
        $this->assertSame(10.0, $length1->nativeValue);
        $this->assertSame(5.0, $length2->originalValue);
        $this->assertSame(5.0, $length2->nativeValue);

        // All results should be different instances
        $this->assertNotSame($length1, $result1);
        $this->assertNotSame($length1, $result2);
        $this->assertNotSame($length1, $result3);
        $this->assertNotSame($result1, $result2);
        $this->assertNotSame($result1, $result3);
        $this->assertNotSame($result2, $result3);
    }

    /**
     * Test that conversion operations don't modify the original instance.
     */
    public function test_conversion_does_not_modify_original(): void
    {
        $length = new Length(1, 'm');

        // Perform conversions
        $inCm = $length->toUnit('cm');
        $inMm = $length->toUnit('mm');
        $inKm = $length->toUnit('km');

        // Original should remain unchanged
        $this->assertSame(1.0, $length->originalValue);
        $this->assertSame('m', $length->nativeUnit->name);
        $this->assertSame(1.0, $length->nativeValue);

        // Verify conversions are correct
        $this->assertSame(100.0, $inCm);
        $this->assertSame(1000.0, $inMm);
        $this->assertSame(0.001, $inKm);
    }

    /**
     * Test that UnitOfMeasurement name is readonly.
     */
    public function test_unit_of_measurement_name_is_readonly(): void
    {
        $unit = new UnitOfMeasurement('m', 1.0);

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Cannot modify readonly property');
        $unit->name = 'km';
    }

    /**
     * Test that operations with different units preserve immutability.
     */
    public function test_operations_with_different_units_preserve_immutability(): void
    {
        $length1 = new Length(1, 'm');
        $length2 = new Length(100, 'cm');

        $result = $length1->add($length2);

        // Both originals should remain unchanged
        $this->assertSame(1.0, $length1->originalValue);
        $this->assertSame(1.0, $length1->nativeValue);
        $this->assertSame(100.0, $length2->originalValue);
        $this->assertSame(1.0, $length2->nativeValue); // 100cm = 1m

        // Result should be correct and in native units
        $this->assertSame(2.0, $result->nativeValue);
        $this->assertNotSame($length1, $result);
        $this->assertNotSame($length2, $result);
    }

    /**
     * Test that Temperature conversions preserve immutability.
     */
    public function test_temperature_conversions_preserve_immutability(): void
    {
        $temp = new Temperature(0, '°C');

        // Perform conversions
        $inKelvin = $temp->toUnit('K');
        $inFahrenheit = $temp->toUnit('°F');

        // Original should remain unchanged
        $this->assertSame(0.0, $temp->originalValue);
        $this->assertSame(273.15, $temp->nativeValue); // 0°C = 273.15K

        // Verify conversions are correct
        $this->assertSame(273.15, $inKelvin);
        $this->assertSame(32.0, $inFahrenheit);
    }

    /**
     * Test that chaining operations creates new instances each time.
     */
    public function test_chaining_operations_creates_new_instances(): void
    {
        $mass = new Mass(100, 'g');

        $result1 = $mass->add(new Mass(50, 'g'));
        $result2 = $result1->add(new Mass(25, 'g'));
        $result3 = $result2->subtract(new Mass(75, 'g'));

        // All instances should be different
        $this->assertNotSame($mass, $result1);
        $this->assertNotSame($mass, $result2);
        $this->assertNotSame($mass, $result3);
        $this->assertNotSame($result1, $result2);
        $this->assertNotSame($result1, $result3);
        $this->assertNotSame($result2, $result3);

        // Original should remain unchanged
        $this->assertSame(100.0, $mass->originalValue);

        // Final result should be correct
        $this->assertSame(100.0, $result3->nativeValue); // 100 + 50 + 25 - 75 = 100
    }

    /**
     * Test that toString method doesn't modify the instance.
     */
    public function test_to_string_does_not_modify_instance(): void
    {
        $length = new Length(10.5, 'm');

        $string1 = (string) $length;
        $string2 = (string) $length;

        $this->assertSame('10.5 m', $string1);
        $this->assertSame('10.5 m', $string2);
        $this->assertSame(10.5, $length->originalValue);
        $this->assertSame('m', $length->nativeUnit->name);
    }

    /**
     * Test that all properties are truly readonly for all quantity types.
     */
    #[DataProvider('readonlyPropertyProvider')]
    public function test_all_properties_are_readonly(PhysicalQuantity $quantity, string $property): void
    {
        $reflection = new \ReflectionProperty($quantity, $property);

        $this->assertTrue(
            $reflection->isReadOnly(),
            "Property {$property} should be readonly"
        );
    }

    /**
     * Data provider for readonly property tests.
     */
    public static function readonlyPropertyProvider(): array
    {
        $quantities = [
            new Length(10, 'm'),
            new Mass(1000, 'g'),
            new Temperature(25, '°C'),
            new Area(10, 'm²'),
            new Volume(10, 'L'),
        ];

        $properties = ['originalValue', 'nativeValue', 'nativeUnit', 'originalUnit'];
        $data = [];

        foreach ($quantities as $quantity) {
            foreach ($properties as $property) {
                $className = new \ReflectionClass($quantity)->getShortName();
                $data["{$className}::{$property}"] = [$quantity, $property];
            }
        }

        return $data;
    }
}

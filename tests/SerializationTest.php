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
final class SerializationTest extends TestCase
{
    public function test_json_serialize_returns_correct_structure(): void
    {
        $length = new Length(100, 'cm');
        $data = $length->jsonSerialize();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('value', $data);
        $this->assertArrayHasKey('unit', $data);
        $this->assertArrayHasKey('nativeValue', $data);
        $this->assertArrayHasKey('nativeUnit', $data);
        $this->assertArrayHasKey('class', $data);
    }

    public function test_json_serialize_preserves_original_values(): void
    {
        $length = new Length(100, 'cm');
        $data = $length->jsonSerialize();

        $this->assertEquals(100, $data['value']);
        $this->assertEquals('cm', $data['unit']);
    }

    public function test_json_serialize_includes_native_values(): void
    {
        $length = new Length(100, 'cm');
        $data = $length->jsonSerialize();

        $this->assertEquals(1, $data['nativeValue']);
        $this->assertEquals('m', $data['nativeUnit']);
    }

    public function test_json_serialize_includes_class_name(): void
    {
        $length = new Length(100, 'cm');
        $data = $length->jsonSerialize();

        $this->assertEquals(Length::class, $data['class']);
    }

    public function test_json_encode_works(): void
    {
        $length = new Length(100, 'cm');
        $json = json_encode($length);

        $this->assertIsString($json);
        $this->assertStringContainsString('"value":100', $json);
        $this->assertStringContainsString('"unit":"cm"', $json);
    }

    public function test_from_json_creates_correct_instance(): void
    {
        $data = [
            'class' => Length::class,
            'value' => 100,
            'unit' => 'cm',
        ];

        $length = PhysicalQuantity::fromJson($data);

        $this->assertInstanceOf(Length::class, $length);
        $this->assertEquals(100, $length->originalValue);
        $this->assertEquals('100 cm', (string) $length);
    }

    public function test_round_trip_serialization(): void
    {
        $original = new Length(1500, 'mm');

        // Serialize
        $json = json_encode($original);
        $data = json_decode($json, true);

        // Deserialize
        $restored = PhysicalQuantity::fromJson($data);

        $this->assertInstanceOf(Length::class, $restored);
        $this->assertEquals($original->originalValue, $restored->originalValue);
        $this->assertEquals((string) $original, (string) $restored);
        $this->assertEquals($original->nativeValue, $restored->nativeValue);
    }

    public function test_from_json_throws_exception_for_missing_class(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('missing required fields');

        PhysicalQuantity::fromJson([
            'value' => 100,
            'unit' => 'cm',
        ]);
    }

    public function test_from_json_throws_exception_for_missing_value(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('missing required fields');

        PhysicalQuantity::fromJson([
            'class' => Length::class,
            'unit' => 'cm',
        ]);
    }

    public function test_from_json_throws_exception_for_missing_unit(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('missing required fields');

        PhysicalQuantity::fromJson([
            'class' => Length::class,
            'value' => 100,
        ]);
    }

    public function test_from_json_throws_exception_for_invalid_class(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('does not exist');

        PhysicalQuantity::fromJson([
            'class' => 'NonExistentClass',
            'value' => 100,
            'unit' => 'cm',
        ]);
    }

    public function test_from_json_throws_exception_for_non_physical_quantity_class(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('is not a PhysicalQuantity');

        PhysicalQuantity::fromJson([
            'class' => \stdClass::class,
            'value' => 100,
            'unit' => 'cm',
        ]);
    }

    public function test_serialization_works_with_different_quantity_types(): void
    {
        $quantities = [
            new Length(100, 'cm'),
            new Mass(1500, 'g'),
            new Temperature(25, '°C'),
        ];

        foreach ($quantities as $quantity) {
            $json = json_encode($quantity);
            $data = json_decode($json, true);
            $restored = PhysicalQuantity::fromJson($data);

            $this->assertInstanceOf($quantity::class, $restored);
            $this->assertEquals($quantity->originalValue, $restored->originalValue);
        }
    }

    public function test_serialization_preserves_precision(): void
    {
        $length = new Length(3.141592653589793, 'm');

        $json = json_encode($length);
        $data = json_decode($json, true);
        $restored = PhysicalQuantity::fromJson($data);

        $this->assertEquals($length->originalValue, $restored->originalValue);
    }

    public function test_serialization_works_with_negative_values(): void
    {
        $temperature = new Temperature(-40, '°C');

        $json = json_encode($temperature);
        $data = json_decode($json, true);
        $restored = PhysicalQuantity::fromJson($data);

        $this->assertInstanceOf(Temperature::class, $restored);
        $this->assertEquals(-40, $restored->originalValue);
    }

    public function test_serialization_includes_correct_native_unit_for_si_units(): void
    {
        $length = new Length(5, 'km');
        $data = $length->jsonSerialize();

        $this->assertEquals(5000, $data['nativeValue']);
        $this->assertEquals('m', $data['nativeUnit']);
    }
}

<?php

namespace Renfordt\UnitLib\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Renfordt\UnitLib\UnitOfMeasurement;

#[CoversClass(UnitOfMeasurement::class)]
class UnitOfMeasurementTest extends TestCase
{
    public function test_constructor_initializes_name(): void
    {
        $unit = new UnitOfMeasurement('meter', 1.0);
        $this->assertSame('meter', $unit->name);
    }

    public function test_constructor_adds_name_to_aliases(): void
    {
        $unit = new UnitOfMeasurement('kilometer', 1000.0);
        $this->assertTrue($unit->isAlias('kilometer'));
    }

    public function test_constructor_sets_conversion_factor(): void
    {
        $unit = new UnitOfMeasurement('gram', 0.001);
        $this->assertSame(0.001, $this->getConversionFactor($unit));
    }

    private function getConversionFactor(UnitOfMeasurement $unit): float
    {
        $reflection = new \ReflectionClass($unit);
        $property = $reflection->getProperty('conversionFactor');
        $property->setAccessible(true);

        return $property->getValue($unit);
    }

    public function test_add_alias_adds_to_aliases(): void
    {
        $unit = new UnitOfMeasurement('meter', 1.0);
        $unit->addAlias('metre');

        $this->assertTrue($unit->isAlias('metre'));
    }

    public function test_add_alias_allows_duplicates(): void
    {
        $unit = new UnitOfMeasurement('meter', 1.0);
        $unit->addAlias('metric');
        $unit->addAlias('metric');

        $this->assertSame(2, array_count_values($this->getAliases($unit))['metric']);
    }

    private function getAliases(UnitOfMeasurement $unit): array
    {
        $reflection = new \ReflectionClass($unit);
        $property = $reflection->getProperty('aliases');
        $property->setAccessible(true);

        return $property->getValue($unit);
    }
}

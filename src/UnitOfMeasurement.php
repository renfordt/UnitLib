<?php

namespace Renfordt\UnitLib;

class UnitOfMeasurement
{
    /**
     * @var array<string>
     */
    protected array $aliases = [];

    public function __construct(public string $name, protected float $conversionFactor)
    {
        $this->aliases[] = $this->name;
    }

    public function addAlias(string $alias): void
    {
        $this->aliases[] = $alias;
    }

    public function isAlias(string $alias): bool
    {
        return in_array($alias, $this->aliases);
    }
}

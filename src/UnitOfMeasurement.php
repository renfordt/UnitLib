<?php

declare(strict_types=1);

namespace Renfordt\UnitLib;

class UnitOfMeasurement
{
    /**
     * @var array<string>
     */
    private array $_aliases = [];

    /**
     * Get all aliases for this unit (including the primary name).
     *
     * @var array<string>
     */
    public array $aliases {
        get => $this->_aliases;
    }

    /**
     * Get the conversion factor relative to the native unit.
     */
    public float $conversionFactor {
        get => $this->_conversionFactor;
    }

    public function __construct(
        public readonly string $name,
        private readonly float $_conversionFactor
    ) {
        $this->_aliases[] = $name;
    }

    /**
     * Add an alias for this unit.
     */
    public function addAlias(string $alias): void
    {
        $this->_aliases[] = $alias;
    }

    /**
     * Check if the given string is an alias for this unit.
     */
    public function isAlias(string $alias): bool
    {
        return in_array($alias, $this->aliases, true);
    }
}

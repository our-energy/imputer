<?php

namespace Imputer\Strategies;

use Imputer\Strategy;

/**
 * Class Substitution
 *
 * Fill gaps by taking applying a separate list of values
 *
 * @package Imputer\Strategies
 */
class Substitution implements Strategy
{
    /**
     * @var array
     */
    protected $substitutes;

    /**
     * Substitution constructor.
     *
     * @param array $substitutes
     */
    public function __construct(array $substitutes)
    {
        $this->substitutes = $substitutes;
    }

    /**
     * @param $previousValue
     * @param $nextValue
     * @param array $keys
     *
     * @return array
     */
    public function generate($previousValue, $nextValue, array $keys): array
    {
        $result = [];

        foreach ($keys as $index => $key) {
            if (isset($this->substitutes[$key])) {
                $result[$index] = $this->substitutes[$key];
            }
        }

        return $result;
    }
}

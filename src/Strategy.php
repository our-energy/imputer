<?php

namespace Imputer;

/**
 * Interface Strategy
 *
 * @package Imputer\Strategies
 */
interface Strategy
{
    /**
     * @param $previousValue
     * @param $nextValue
     * @param array $keys
     *
     * @return array
     */
    public function generate($previousValue, $nextValue, array $keys): array;
}

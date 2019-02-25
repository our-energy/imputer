<?php

namespace Imputer\Strategies;

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
     * @param $numSteps
     *
     * @return mixed
     */
    public function generate($previousValue, $nextValue, $numSteps): array;
}

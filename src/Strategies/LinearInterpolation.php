<?php

namespace Imputer\Strategies;

use Imputer\Strategy;

/**
 * Class LinearInterpolation
 *
 * Generate linear steps between two sample points
 *
 * e.g. 3 steps between 1.0 and 3.0 will create [1.5, 2.0, 2.5]
 *
 * @package Imputer\Strategies
 */
class LinearInterpolation implements Strategy
{
    /**
     * @param $previousValue
     * @param $nextValue
     * @param array $keys
     *
     * @return array
     */
    public function generate($previousValue, $nextValue, array $keys): array
    {
        $numSteps = count($keys);

        return $this->linearInterpolate($previousValue, $nextValue, $numSteps);
    }

    /**
     * @param $previousValue
     * @param $nextValue
     * @param $numSteps
     *
     * @return array
     */
    protected function linearInterpolate($previousValue, $nextValue, $numSteps): array
    {
        $stepValue = ($nextValue - $previousValue) / ($numSteps + 1);

        $values = [];

        for ($i = 1; $i <= $numSteps; $i++) {
            $values[] = $previousValue + ($stepValue * $i);
        }

        return $values;
    }
}

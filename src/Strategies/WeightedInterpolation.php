<?php

namespace Imputer\Strategies;

/**
 * Class WeightedInterpolation
 *
 * Applies a weight to each linear interpolated value
 *
 * @package Imputer\Strategies
 */
class WeightedInterpolation extends LinearInterpolation
{
    /**
     * @var array
     */
    protected $weights;

    /**
     * WeightedInterpolation constructor.
     *
     * @param array $weights
     */
    public function __construct(array $weights)
    {
        $this->weights = $weights;
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
        $numSteps = count($keys);

        $result = $this->linearInterpolate($previousValue, $nextValue, $numSteps);

        foreach ($result as $index => &$value) {
            $key = $keys[$index];

            if (isset($this->weights[$key])) {
                $value *= $this->weights[$key];
            }
        }

        return $result;
    }
}

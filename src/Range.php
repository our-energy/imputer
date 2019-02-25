<?php

namespace Imputer;

/**
 * Class Range
 *
 * @package Imputer
 */
class Range
{
    /**
     * @var array
     */
    protected $keyList;

    /**
     * @var array
     */
    protected $previousValue;

    /**
     * @var array
     */
    protected $nextValue;

    /**
     * @var Strategy
     */
    protected $strategy;

    /**
     * @var int
     */
    protected $numSteps;

    /**
     * Range constructor.
     *
     * @param array $keyList
     * @param $previousValue
     * @param $nextValue
     * @param Strategy $strategy
     */
    public function __construct(array $keyList, $previousValue, $nextValue, Strategy $strategy)
    {
        $this->keyList = $keyList;
        $this->previousValue = $previousValue;
        $this->nextValue = $nextValue;
        $this->strategy = $strategy;

        $this->numSteps = count($keyList);
    }

    /**
     * @return array
     */
    public function generate(): array
    {
        // Compute the missing values using the supplied strategy
        $newValues = $this->strategy->generate(
            $this->previousValue,
            $this->nextValue,
            $this->keyList
        );

        $result = [];

        // Map the new values into the existing data
        foreach ($this->keyList as $index => $key) {
            $result[$key] = $newValues[$index];
        }

        return $result;
    }
}

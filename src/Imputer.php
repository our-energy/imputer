<?php

namespace Imputer;

use Imputer\Strategies\Strategy;

/**
 * Class Imputer
 */
class Imputer
{
    /**
     * @var array
     */
    protected $keys;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var Strategy
     */
    protected $strategy;

    /**
     * Imputer constructor.
     *
     * @param array $keys
     * @param array $data
     * @param Strategy $strategy
     */
    public function __construct(array $keys, array $data, Strategy $strategy)
    {
        $this->keys = $keys;
        $this->data = $data;
        $this->strategy = $strategy;
    }

    /**
     * @return array
     */
    private function buildRanges(): array
    {
        $ranges = [];
        $missingKeys = [];

        // Identify the keys with missing values
        foreach ($this->keys as $index => $key) {
            if (!isset($this->data[$key])) {
                $missingKeys[$index] = $key;
            }
        }

        $missingKeyIndexes = array_keys($missingKeys);

        // Build a list of ranges we need to interpolate over
        for ($i = 0; $i < count($missingKeyIndexes); $i++) {
            $key = $missingKeyIndexes[$i];

            if ($i > 0 && ($missingKeyIndexes[$i - 1] === $missingKeyIndexes[$i] - 1)) {
                array_push($ranges[count($ranges) - 1], $key);
            } else {
                array_push($ranges, [$key]);
            }
        }

        foreach ($ranges as &$range) {
            $keyList = array_slice($this->keys, reset($range), count($range));

            $previousValue = $this->getPreviousKnownValue(reset($range));
            $nextValue = $this->getNextKnownValue(end($range));

            $range = new Range($keyList, $previousValue, $nextValue, $this->strategy);
        }

        return $ranges;
    }

    /**
     * @param $missingIndex
     *
     * @return mixed|null
     */
    private function getPreviousKnownValue($missingIndex)
    {
        for ($i = $missingIndex; $i >= 0; $i--) {
            $key = $this->keys[$i];

            if (isset($this->data[$key])) {
                return $this->data[$key];
            }
        }

        return null;
    }

    /**
     * @param $missingIndex
     *
     * @return mixed|null
     */
    private function getNextKnownValue($missingIndex)
    {
        for ($i = $missingIndex; $i <= count($this->keys); $i++) {
            $key = $this->keys[$i];

            if (isset($this->data[$key])) {
                return $this->data[$key];
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function generate(): array
    {
        $ranges = $this->buildRanges();

        $result = $this->data;

        /** @var Range $range */
        foreach ($ranges as $range) {
            $rangeResult = $range->generate();

            foreach ($rangeResult as $key => $value) {
                $result[$key] = $value;
            }
        }

        ksort($result);

        return $result;
    }
}

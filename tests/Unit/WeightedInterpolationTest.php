<?php

namespace Imputer\Tests;

use Imputer\Imputer;
use Imputer\Strategies\WeightedInterpolation;
use PHPUnit\Framework\TestCase;

class WeightedInterpolationTest extends TestCase
{
    public function testNormalRange()
    {
        $keys = [
            '2019-01-01',
            '2019-01-02',
            '2019-01-03',
            '2019-01-04',
            '2019-01-05',
            '2019-01-06',
            '2019-01-07',
            '2019-01-08',
            '2019-01-09',
            '2019-01-10'
        ];

        $knownData = [
            '2019-01-01' => 1,
            '2019-01-05' => 5,
            '2019-01-10' => 10
        ];

        $weights = [
            '2019-01-02' => 0.2,
            '2019-01-03' => 0.3,
            '2019-01-04' => 0.2,
            '2019-01-06' => 0.0,
            '2019-01-07' => 0.1,
            '2019-01-08' => 0.2,
            '2019-01-09' => 0.3,
        ];

        $expected = [
            '2019-01-01' => 1,
            '2019-01-02' => 0.4,
            '2019-01-03' => 0.9,
            '2019-01-04' => 0.8,
            '2019-01-05' => 5,
            '2019-01-06' => 0,
            '2019-01-07' => 0.7,
            '2019-01-08' => 1.6,
            '2019-01-09' => 2.7,
            '2019-01-10' => 10
        ];

        $strategy = new WeightedInterpolation($weights);
        $imputer = new Imputer($keys, $knownData, $strategy);

        $result = $imputer->generate();

        $this->assertEquals($expected, $result);
    }

    public function testMissingWeights()
    {
        $keys = [
            '2019-01-01',
            '2019-01-02',
            '2019-01-03',
            '2019-01-04',
            '2019-01-05',
            '2019-01-06',
            '2019-01-07',
            '2019-01-08',
            '2019-01-09',
            '2019-01-10'
        ];

        $knownData = [
            '2019-01-01' => 1,
            '2019-01-05' => 5,
            '2019-01-10' => 10
        ];

        $weights = [
            '2019-01-03' => 0.3,
            '2019-01-07' => 0.1
        ];

        $expected = [
            '2019-01-01' => 1,
            '2019-01-02' => 2,
            '2019-01-03' => 0.9,
            '2019-01-04' => 4,
            '2019-01-05' => 5,
            '2019-01-06' => 6,
            '2019-01-07' => 0.7,
            '2019-01-08' => 8,
            '2019-01-09' => 9,
            '2019-01-10' => 10
        ];

        $strategy = new WeightedInterpolation($weights);
        $imputer = new Imputer($keys, $knownData, $strategy);

        $result = $imputer->generate();

        $this->assertEquals($expected, $result);
    }
}

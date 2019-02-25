<?php

namespace Imputer\Tests;

use Imputer\Imputer;
use Imputer\Strategies\LinearInterpolation;
use PHPUnit\Framework\TestCase;

class LinearInterpolationTest extends TestCase
{
    public function testNormalRange()
    {
        $keys = range(0, 5);

        $knownData = [
            0 => 10,
            2 => 30,
            5 => 60
        ];

        $expected = [
            0 => 10,
            1 => 20,
            2 => 30,
            3 => 40,
            4 => 50,
            5 => 60
        ];

        $strategy = new LinearInterpolation();
        $imputer = new Imputer($keys, $knownData, $strategy);

        $result = $imputer->generate();

        $this->assertEquals($expected, $result);
    }

    public function testNonNumericKeys()
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
            '2019-01-10' => 10
        ];

        $expected = [
            '2019-01-01' => 1,
            '2019-01-02' => 2,
            '2019-01-03' => 3,
            '2019-01-04' => 4,
            '2019-01-05' => 5,
            '2019-01-06' => 6,
            '2019-01-07' => 7,
            '2019-01-08' => 8,
            '2019-01-09' => 9,
            '2019-01-10' => 10
        ];

        $strategy = new LinearInterpolation();
        $imputer = new Imputer($keys, $knownData, $strategy);

        $result = $imputer->generate();

        $this->assertEquals($expected, $result);
    }

    public function testEmptySet()
    {
        $keys = [];
        $knownData = [];
        $expected = [];

        $strategy = new LinearInterpolation();
        $imputer = new Imputer($keys, $knownData, $strategy);

        $result = $imputer->generate();

        $this->assertEquals($expected, $result);
    }

    public function testReverseInterpolation()
    {
        $keys = range(0, 5);

        $knownData = [
            0 => 10,
            5 => 0
        ];

        $expected = [
            0 => 10,
            1 => 8,
            2 => 6,
            3 => 4,
            4 => 2,
            5 => 0
        ];

        $strategy = new LinearInterpolation();
        $imputer = new Imputer($keys, $knownData, $strategy);

        $result = $imputer->generate();

        $this->assertEquals($expected, $result);
    }

    public function testNegativeInterpolation()
    {
        $keys = range(0, 5);

        $knownData = [
            0 => -10,
            5 => -60
        ];

        $expected = [
            0 => -10,
            1 => -20,
            2 => -30,
            3 => -40,
            4 => -50,
            5 => -60
        ];

        $strategy = new LinearInterpolation();
        $imputer = new Imputer($keys, $knownData, $strategy);

        $result = $imputer->generate();

        $this->assertEquals($expected, $result);
    }

    public function testDecimalInterpolation()
    {
        $keys = range(0, 9);

        $knownData = [
            0 => 0.001,
            9 => 0.007
        ];

        $expected = [
            0.001,
            0.0016666666666667,
            0.0023333333333333,
            0.003,
            0.0036666666666667,
            0.0043333333333333,
            0.005,
            0.0056666666666667,
            0.0063333333333333,
            0.007
        ];

        $strategy = new LinearInterpolation();
        $imputer = new Imputer($keys, $knownData, $strategy);

        $result = $imputer->generate();

        $this->assertEquals($expected, $result);
    }

    public function testMultipleRanges()
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
            '2019-01-10',
            '2019-01-11',
            '2019-01-12',
            '2019-01-13',
            '2019-01-14',
            '2019-01-15',
            '2019-01-16',
            '2019-01-17',
            '2019-01-18',
            '2019-01-19',
            '2019-01-20'
        ];

        $knownData = [
            '2019-01-01' => 0,
            '2019-01-02' => 1,
            // ...
            '2019-01-05' => 4,
            // ...
            '2019-01-07' => 6,
            // ...
            '2019-01-10' => 9,
            '2019-01-11' => 10,
            '2019-01-12' => 11,
            '2019-01-13' => 12,
            // ...
            '2019-01-20' => 19
        ];

        $expected = [
            '2019-01-01' => 0,
            '2019-01-02' => 1,
            '2019-01-03' => 2,
            '2019-01-04' => 3,
            '2019-01-05' => 4,
            '2019-01-06' => 5,
            '2019-01-07' => 6,
            '2019-01-08' => 7,
            '2019-01-09' => 8,
            '2019-01-10' => 9,
            '2019-01-11' => 10,
            '2019-01-12' => 11,
            '2019-01-13' => 12,
            '2019-01-14' => 13,
            '2019-01-15' => 14,
            '2019-01-16' => 15,
            '2019-01-17' => 16,
            '2019-01-18' => 17,
            '2019-01-19' => 18,
            '2019-01-20' => 19
        ];

        $strategy = new LinearInterpolation();
        $imputer = new Imputer($keys, $knownData, $strategy);

        $result = $imputer->generate();

        $this->assertEquals($expected, $result);
    }
}

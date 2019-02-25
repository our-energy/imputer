<?php

namespace Imputer\Tests;

use Imputer\Imputer;
use Imputer\Strategies\Substitution;
use PHPUnit\Framework\TestCase;

class SubstitutionTest extends TestCase
{
    public function testNormalRange()
    {
        $keys = range(0, 5);

        $knownData = [
            0 => 10,
            2 => 30,
            5 => 60
        ];

        $substitutes = [
            1 => 'a',
            3 => 'b'
        ];

        $expected = [
            0 => 10,
            1 => 'a',
            2 => 30,
            3 => 'b',
            4 => null,
            5 => 60
        ];

        $strategy = new Substitution($substitutes);
        $imputer = new Imputer($keys, $knownData, $strategy);

        $result = $imputer->generate();

        $this->assertEquals($expected, $result);
    }

    public function testNoMatchingSubstitutes()
    {
        $keys = range(0, 5);

        $knownData = [
            0 => 10,
            2 => 30,
            5 => 60
        ];

        $substitutes = [
            10 => 'a',
            30 => 'b'
        ];

        $expected = [
            0 => 10,
            1 => null,
            2 => 30,
            3 => null,
            4 => null,
            5 => 60
        ];

        $strategy = new Substitution($substitutes);
        $imputer = new Imputer($keys, $knownData, $strategy);

        $result = $imputer->generate();

        $this->assertEquals($expected, $result);
    }

    public function testDefaultValue()
    {
        $keys = range(0, 5);

        $knownData = [
            0 => 10,
            2 => 30,
            5 => 60
        ];

        $substitutes = [
            3 => 'b'
        ];

        $expected = [
            0 => 10,
            1 => 'x',
            2 => 30,
            3 => 'b',
            4 => 'x',
            5 => 60
        ];

        $strategy = new Substitution($substitutes, 'x');
        $imputer = new Imputer($keys, $knownData, $strategy);

        $result = $imputer->generate();

        $this->assertEquals($expected, $result);
    }
}

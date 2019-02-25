[![Build Status](https://travis-ci.org/our-energy/imputer.svg?branch=master)](https://travis-ci.org/our-energy/imputer)
[![Latest Stable Version](https://poser.pugx.org/ourenergy/imputer/v/stable?format=flat)](https://packagist.org/packages/ourenergy/imputer)

# Imputer

A library for filling in missing data by applying strategies. Inspired by [php-ai/php-ml](https://github.com/php-ai/php-ml).

## Installation

```
composer require ourenergy/imputer
```

## Usage

Imputer works by taking a list of array keys, and a partial set of values, then filling in the gaps. A gap is any key
which exists in the key list, which doesn't have a corresponding value.

Say we had a set of incomplete data;

```php
$knownData = [
    0 => 0.5,
 // 1 => ???
    2 => 1.0,
    3 => 1.2,
 // 4 => ???
    5 => 3.0
];
```

We can apply a linear interpolation to guess at what the values in between might be;

```php
$keys = [
    0,
    1,
    2,
    3,
    4,
    5
];

$knownData = [
    0 => 0.5,
    2 => 1.0,
    3 => 1.2,
    5 => 3.0
];

$imputer = new Imputer($keys, $knownData, new LinearInterpolation());

$result = $imputer->generate();
```

The result will be a completed set of data;

```
Array
(
    [0] => 0.5
    [1] => 0.75
    [2] => 1
    [3] => 1.2
    [4] => 2.1
    [5] => 3
)
```

## Strategies

Out of the box Imputer provides a few basic strategies. You can write your own by implementing the `Imputer\Strategy`
interface.

### Linear Interpolation

As described above, the `LinearInterpolation` strategy will generate a number of steps between the previous and next 
known values.

e.g. 3 steps between 1.0 and 3.0 will create `[1.5, 2.0, 2.5]`

### Weighted Interpolation

Similar to the `LinearInterpolation` strategy, it will interpolate missing values, but with the added capability of 
applying a weight to each result. This can be useful for applying historical trends to computed data or when you don't
want a straight line between points on your graph.

```php
$keys = range(0, 5);

$knownData = [
    0 => 10,
    2 => 30,
    5 => 60
];

$weights = [
    1 => 1,
    3 => 0.7,
    4 => 0.5
];

$strategy = new WeightedInterpolation($weights);
$imputer = new Imputer($keys, $knownData, $strategy);

$result = $imputer->generate();
```

Will result in the following;

```
Array
(
    [0] => 10
    [1] => 20
    [2] => 30
    [3] => 28
    [4] => 25
    [5] => 60
)
```

### Substitution

The `Substitution` strategy will take an existing set of values and use them to fill in any gaps.

```php
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

$strategy = new Substitution($substitutes, 'x');
$imputer = new Imputer($keys, $knownData, $strategy);

$result = $imputer->generate();
```

Will result in the following;

```
Array
(
    [0] => 10
    [1] => a
    [2] => 30
    [3] => b
    [4] => x
    [5] => 60
)
```

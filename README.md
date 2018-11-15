Stringify
=========

[![Latest Stable Version](https://img.shields.io/packagist/v/mf/stringify.svg)](https://packagist.org/packages/mf/stringify)
[![Build Status](https://travis-ci.com/MortalFlesh/stringify.svg?branch=master)](https://travis-ci.com/MortalFlesh/stringify)
[![Coverage Status](https://coveralls.io/repos/github/MortalFlesh/stringify/badge.svg?branch=master)](https://coveralls.io/github/MortalFlesh/stringify?branch=master)

Simple and tiny class (function) to stringify anything in PHP.

## Installation

```bash
composer require mf/stringify
```

## Usage

### By class and static method

```php
use MF\Stringify\Stringify;

echo Stringify::stringify([1, 2, 3]);   // "[1, 2, 3]"
```

### By standalone function
```php
use function MF\Stringify\stringify;

echo stringify([1, 2, 3]);   // "[1, 2, 3]"
```

**Bonus**: Standalone function may be used through a constant with its FQN
```php
use const MF\Stringify\stringify;

$result = array_map(stringify, [1, 'two']); // ['1', '"two"']
```

## Example

_NOTE: values longer than 100 chars is shrinked to 100 chars with `...` suffix_

For easier examples, let's use a standalone function

| Type | PHP | Result (_string_) |
| ---  | --- | ---    |
| NULL | `stringify(null);` | `null` |
| bool | `stringify(true);` | `true` |
| bool | `stringify(false);` | `false` |
| string | `stringify('');` | `""` |
| string | `stringify('Some string');` | `"Some string"` |
| int | `stringify(42);` | `42` |
| float | `stringify(3.14);` | `3.14` |
| array | `stringify([1, 2, 3]);` | `[1, 2, 3]` |
| array | `stringify(['foo' => 'bar']);` | `["foo" => "bar"]` |
| array | `stringify(['person' => ['name' => 'Peter Parker'], 'alterego' => 'spider-man']);` | `["person" => ["name" => "Peter Parker"], "alterego" => "spider-man"]` |
| object | `stringify(new \Foo\Bar());` | `Foo\Bar` |
| object | `stringify(new \DateTime());` | `DateTime { 2018-11-15T10:20:30+00:00 }` |
| object | `stringify(Seq::range('1..4'));` | `MF\Collection\Immutable\Seq [1, 2, 3, 4]` |

## Changelog
For latest changes see [CHANGELOG.md](CHANGELOG.md) file. We follow [Semantic Versioning](https://semver.org/).

## Contributing and development

### Install dependencies

```bash
composer install
```

### Run tests

For each pull-request, unit tests as well as static analysis and codestyle checks must pass.

To run all those checks execute:

```bash
composer all
```

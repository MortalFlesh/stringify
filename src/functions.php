<?php declare(strict_types=1);

namespace MF\Stringify;

const stringify = __NAMESPACE__ . '\\stringify';

/**
 * @param mixed $value of any type
 *
 * @example
 * stringify(null);              // null
 * stringify(true);              // true
 * stringify(false);             // false
 * stringify('');                // ""
 * stringify('Some string');     // "Some string"
 * stringify(42);                // 42
 * stringify(3.14);              // 3.14
 * stringify([1, 2, 3]);         // [1, 2, 3]
 * stringify(['foo' => 'bar']);  // ["foo" => "bar"]
 * stringify(new \Foo\Bar());    // Foo\Bar
 */
function stringify($value, bool $shrinkLongOutput = false): string
{
    return Stringify::stringify($value, $shrinkLongOutput);
}

<?php declare(strict_types=1);

namespace MF\Stringify;

/** @deprecated It is not that useful now, since the stringify(...) notation */
const stringify = __NAMESPACE__ . '\\stringify';

/**
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
function stringify(mixed $value, bool $shrinkLongOutput = false): string
{
    return Stringify::stringify($value, $shrinkLongOutput);
}

/**
 * Works the same as a \sprintf() function but with additional %A placeholder.
 *
 * %A placeholder stringify any value.
 * @see stringify()
 *
 * @phpstan-param mixed $args
 */
function sprintf(string $format, ...$args): string
{
    if (str_contains($format, '%A')) {
        preg_match_all('/((?<!%)%[a-zA-Z])/', $format, $matches);
        $matches = $matches[0] ?? [];
        $format = (string) preg_replace('/((?<!%)%A)/', '%s', $format);

        $modifiedArgs = [];
        foreach ($args as $i => $arg) {
            $modifiedArgs[$i] = ($matches[$i] ?? null) === '%A'
                 ? stringify($arg)
                 : $arg;
        }

        $args = $modifiedArgs;
    }

    return \sprintf($format, ...$args);
}

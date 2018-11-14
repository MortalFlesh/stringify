<?php declare(strict_types=1);

namespace MF\Stringify;

class Stringify
{
    /**
     * @example
     * Stringify::stringify(null);              // null
     * Stringify::stringify(true);              // true
     * Stringify::stringify(false);             // false
     * Stringify::stringify('');                // ""
     * Stringify::stringify('Some string');     // "Some string"
     * Stringify::stringify(42);                // 42
     * Stringify::stringify(3.14);              // 3.14
     * Stringify::stringify([1, 2, 3]);         // [1, 2, 3]
     * Stringify::stringify(['foo' => 'bar']);  // ["foo" => "bar"]
     * Stringify::stringify(new \Foo\Bar());    // Foo\Bar
     *
     * @param mixed $value of any type
     */
    public static function stringify($value): string
    {
        if ($value === null) {
            return 'null';
        }

        if (\is_bool($value)) {
            return $value
                ? 'true'
                : 'false';
        }

        if (\is_string($value)) {
            return sprintf('"%s"', self::shrink($value));
        }

        if (\is_scalar($value)) {
            return self::shrink((string) $value);
        }

        if (\is_array($value)) {
            return self::stringifyArray($value);
        }

        if (\is_object($value)) {
            return \get_class($value);
        }

        if (\is_resource($value)) {
            return \get_resource_type($value);
        }

        return \gettype($value);
    }

    private static function shrink(string $value): string
    {
        return \strlen($value) > 100
            ? sprintf('%s...', \substr($value, 0, 97))
            : $value;
    }

    private static function stringifyArray(array $value): string
    {
        if (empty($value)) {
            return '[]';
        }

        $keys = array_keys($value);
        $values = array_values($value);
        [$firstKey] = $keys;
        $ignoreKeys = $firstKey === 0;

        return sprintf('[%s]', self::shrink(implode(', ', array_map(function ($key, $value) use ($ignoreKeys) {
            return $ignoreKeys
                ? self::stringify($value)
                : sprintf('%s => %s', self::stringify($key), self::stringify($value));
        }, $keys, $values))));
    }
}

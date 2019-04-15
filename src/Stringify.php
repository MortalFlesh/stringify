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
    public static function stringify($value, bool $fullOutput = false): string
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
            return sprintf('"%s"', self::shrink($value, $fullOutput));
        }

        if (\is_scalar($value)) {
            return self::shrink((string) $value, $fullOutput);
        }

        if (\is_array($value)) {
            return self::stringifyArray($value, $fullOutput);
        }

        if (\is_object($value)) {
            return self::stringifyObject($value, $fullOutput);
        }

        if (\is_resource($value)) {
            return sprintf('resource<%s>', \get_resource_type($value));
        }

        return \gettype($value);
    }

    private static function shrink(string $value, bool $fullOutput): string
    {
        return !$fullOutput && \strlen($value) > 100
            ? sprintf('%s...', \substr($value, 0, 97))
            : $value;
    }

    private static function stringifyArray(array $value, bool $fullOutput): string
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
        }, $keys, $values)), $fullOutput));
    }

    private static function stringifyObject($value, bool $fullOutput): string
    {
        $valueClass = \get_class($value);

        if ($value instanceof \Throwable) {
            return sprintf(
                '%s { "%s", %s, %s #%s }',
                $valueClass,
                $value->getMessage(),
                $value->getCode(),
                $value->getFile(),
                $value->getLine()
            );
        }

        if (method_exists($value, '__toString')) {
            return sprintf('%s { %s }', $valueClass, $value->__toString());
        }

        if (method_exists($value, 'toString')) {
            return sprintf('%s { %s }', $valueClass, $value->toString());
        }

        if ($value instanceof \Traversable) {
            return sprintf('%s %s', $valueClass, self::stringifyArray(iterator_to_array($value), $fullOutput));
        }

        if ($value instanceof \DateTimeInterface) {
            return sprintf('%s { %s }', $valueClass, $value->format(\DateTime::ATOM));
        }

        if (interface_exists(\JsonSerializable::class) && function_exists('json_encode') && $value instanceof \JsonSerializable) {
            return sprintf(
                '%s {%s}',
                $valueClass,
                self::shrink(\trim((string) \json_encode($value->jsonSerialize()), '{}'), $fullOutput)
            );
        }

        return $valueClass;
    }
}

<?php declare(strict_types=1);

namespace MF\Stringify;

class Format
{
    public static function format(string $format, ...$args): string
    {
        return \mb_strpos($format, '%A') !== false
            ? self::stringifyValuesAndFormat($format, $args)
            : \sprintf($format, ...$args);
    }

    private static function stringifyValuesAndFormat(string $format, array $args): string
    {
        $startsWithPlaceholder = $format[0] ?? '' === '%';

        $i = $startsWithPlaceholder
            ? -1
            : 0;

        var_dump([__METHOD__, $format, $args, $i]);


        return array_reduce(
            self::findPlaceholders($format),
            function (string $acc, $placeholder) use ($startsWithPlaceholder, &$i, $args): string {
                if ($placeholder[0] === 'A') {
                    var_dump([
                        'stringify' => [
                            $args,
                            $i,
                            $args[$i] ?? '<not found>',
                            Stringify::stringify($args[$i] ?? '<not found>'),
                        ],
                    ]);

                    return $acc . Stringify::stringify($args[$i++]);
                } elseif (preg_match('/^(\d+\$\w{1}){1}(.*)$/', $placeholder, $matches)) {
                    array_shift($matches);

                    var_dump(['matches' => $matches]);
                }

                var_dump($acc, $placeholder);

                $i++;

                return $startsWithPlaceholder
                    ? $acc . $placeholder
                    : $acc . '%' . $placeholder;
            },
            ''
        );
    }

    private static function findPlaceholders(string $format): array
    {
        return explode('%', $format);
    }
}

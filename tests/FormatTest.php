<?php declare(strict_types=1);

namespace MF\Stringify;

/**
 * @group unit
 */
class FormatTest extends AbstractTestCase
{
    /** @dataProvider provideFormatWithValues */
    public function testShouldFormatAndStringifyValues(string $format, array $args, string $expected)
    {
        $result = Format::format($format, ...$args);

        $this->assertSame($expected, $result);
    }

    public function provideFormatWithValues(): array
    {
        return [
            // format, args, expected
            'empty' => ['', ['foo', 'bar'], ''],
            'simple sprintf' => ['hello %s!', ['world'], 'hello world!'],
            'with more values' => [
                '%s %s! You are number %d!',
                ['Hello', 'world', 1],
                'Hello world! You are number 1!',
            ],
            'with stringify' => [
                'Stringify: %A',
                [[1, 2, 3]],
                'Stringify: [1, 2, 3]',
            ],
            'with more values including stringify' => [
                '%A %A! %s are number %A!',
                ['Hello', 'world', 'You', 1],
                'Hello world! You are number 1!',
            ],
            'with percent' => [
                '%A%%!',
                [100],
                '100%!',
            ],
            'with padding' => [
                "%09d",
                [123],
                '000000123',
            ],
            'with padding and stringify' => [
                "%09d and %A",
                [123, 321],
                '000000123 and 321',
            ],
            'with repeated values' => [
                'The %2$s contains %1$d monkeys. That\'s a nice %2$s full of %1$d monkeys.',
                [10, 123],
                'The 123 contains 10 monkeys. That\'s a nice 123 full of 10 monkeys.',
            ],
            'with repeated values and stringify' => [
                'The %2$s contains %1$d monkeys. That\'s a nice %2$s full of %1$d %A.',
                [10, 123, 'monkeys'],
                'The 123 contains 10 monkeys. That\'s a nice 123 full of 10 monkeys.',
            ],
        ];
    }
}

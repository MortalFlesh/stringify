<?php declare(strict_types=1);

namespace MF\Stringify;

/**
 * @group unit
 */
class SprintfTest extends AbstractTestCase
{
    /** @dataProvider provideValues */
    public function testShouldFormatValuesToString(string $format, array $args, string $expected): void
    {
        $result = sprintf($format, ...$args);

        $this->assertSame($expected, $result);
    }

    public static function provideValues(): iterable
    {
        return [
            'empty' => ['', [], ''],
            'string' => ['%s', ['foo'], 'foo'],
            'string with multiple args' => ['%s %s', ['foo', 'bar'], 'foo bar'],
            'string with numbers' => ['%s %d', ['foo', 42], 'foo 42'],
            'string with float' => ['%s %f', ['foo', 42.42], 'foo 42.420000'],
            'string with bool' => ['%s %b', ['foo', true], 'foo 1'],
            'string with array' => ['%s %A', ['foo', [1, 2, 3]], 'foo [1, 2, 3]'],
            'string with multiple arrays' => [
                '%s %A %A',
                ['foo', [1, 2, 3], ['foo' => 'bar']],
                'foo [1, 2, 3] ["foo" => "bar"]',
            ],
            'string with object' => ['%s %A', ['foo', new \stdClass()], 'foo stdClass'],
            'string with text and values' => [
                'Hello %A, your age is %A',
                ['Jon', 42],
                'Hello "Jon", your age is 42',
            ],
            'string with more values than placeholders' => [
                'Hello %A, your age is %d',
                ['Jon', 42, 3, 2, 1],
                'Hello "Jon", your age is 42',
            ],
            'string with % in it' => [
                'Hello %A, your age is %d%% and that %% is not a placeholder',
                ['Jon', 42],
                'Hello "Jon", your age is 42% and that % is not a placeholder',
            ],
            'string with % followed by a char in it' => [
                'Hello %A, your age is %d%% and that %%char is not a placeholder',
                ['Jon', 42],
                'Hello "Jon", your age is 42% and that %char is not a placeholder',
            ],
            'string with % followed by the A char in it' => [
                'Hello %A, your age is %d%% and that %%A char is not a placeholder',
                ['Jon', 42],
                'Hello "Jon", your age is 42% and that %A char is not a placeholder',
            ],
        ];
    }
}

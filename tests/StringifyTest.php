<?php declare(strict_types=1);

namespace MF\Stringify;

/**
 * @group unit
 */
class StringifyTest extends AbstractTestCase
{
    /**
     * @dataProvider provideValue
     *
     * @param mixed $value of any type
     */
    public function testShouldStringifyValue($value, string $expected): void
    {
        $result = Stringify::stringify($value);

        $this->assertSame($expected, $result);
    }

    public function provideValue(): array
    {
        return [
            // value, expected
            'null' => [null, 'null'],
            // string
            'empty string' => ['', '""'],
            'single space' => [' ', '" "'],
            'quoted string' => ['name "Jon"', '"name "Jon""'],
            'long string' => [
                '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789ab',
                '"0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456..."',
            ],
            // integer
            'int' => [42, '42'],
            'negative int' => [-42, '-42'],
            // float
            'float' => [-42.42, '-42.42'],
            'negative float' => [-42.42, '-42.42'],
            // bool
            'true' => [true, 'true'],
            'false' => [false, 'false'],
            // class
            'class $this' => [$this, 'MF\\Stringify\\StringifyTest'],
            // resource
            'resource' => [fopen(__DIR__ . '/StringifyTest.php', 'r'), 'stream'],
            // array
            'array - empty' => [[], '[]'],
            'array - of mixed' => [
                [
                    null,
                    true,
                    false,
                    'string',
                    42,
                    3.14,
                    [1, 2, 3],
                    $this,
                    fopen(__DIR__ . '/StringifyTest.php', 'r'),
                ],
                '[null, true, false, "string", 42, 3.14, [1, 2, 3], MF\\Stringify\\StringifyTest, stream]',
            ],
            'array - nested ints' => [
                [1, [2, [3, 4]]],
                '[1, [2, [3, 4]]]',
            ],
            'array - ints with specific keys' => [
                [10 => 1, [11 => 2, [12 => 3, 4]]],
                '[10 => 1, 11 => [11 => 2, 12 => [12 => 3, 13 => 4]]]',
            ],
            'array - nested with keys' => [
                ['person' => ['name' => 'Peter Parker'], 'alterego' => 'spider-man'],
                '["person" => ["name" => "Peter Parker"], "alterego" => "spider-man"]',
            ],
        ];
    }

    /**
     * @dataProvider provideValue
     *
     * @param mixed $value of any type
     */
    public function testShouldStringifyValueViaFunction($value, string $expected): void
    {
        $result = stringify($value);

        $this->assertSame($expected, $result);
    }

    public function testShouldStringifyValuesByCallback(): void
    {
        $result = array_map(stringify, [1, 'two']);

        $this->assertSame(['1', '"two"'], $result);
    }
}

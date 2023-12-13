<?php declare(strict_types=1);

namespace MF\Stringify;

use MF\Collection\Immutable\Generic\Seq;
use MF\Collection\Mutable\Generic\Map;
use MF\Stringify\Fixture\Entity;
use MF\Stringify\Fixture\Json;
use MF\Stringify\Fixture\SomeClass;
use MF\Stringify\Fixture\SomeException;
use MF\Stringify\Fixture\StringableEntity;

/**
 * @group unit
 */
class StringifyTest extends AbstractTestCase
{
    /** @dataProvider provideShrinkedValue */
    public function testShouldStringifyValue(mixed $value, string $expected): void
    {
        $result = Stringify::stringify($value, true);

        $this->assertSame($expected, $result);
    }

    public static function provideShrinkedValue(): array
    {
        return [
            // value, expected
            'null' => [null, 'null'],
            // string
            'empty string' => ['', '""'],
            'single space' => [' ', '" "'],
            'quoted string' => ['name "Jon"', '"name "Jon""'],
            'long string' => [
                self::longString(100, 'ab'),
                sprintf('"%s"', self::longString(97, '...')),
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
            'class' => [new SomeClass(), 'MF\\Stringify\\Fixture\\SomeClass'],
            // resource
            'resource' => [fopen(__DIR__ . '/StringifyTest.php', 'rb'), 'resource<stream>'],
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
                    new SomeClass(),
                    fopen(__DIR__ . '/StringifyTest.php', 'rb'),
                    self::class,
                ],
                '[null, true, false, "string", 42, 3.14, [1, 2, 3], MF\\Stringify\\Fixture\\SomeClass, resource<stream...]',
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
            // Traversable
            'sequence of ints' => [Seq::range('1..4'), 'MF\Collection\Immutable\Generic\Seq [1, 2, 3, 4]'],
            'map' => [
                Map::from(['firstName' => 'Peter', 'surname' => 'Parker']),
                'MF\Collection\Mutable\Generic\Map ["firstName" => "Peter", "surname" => "Parker"]',
            ],
            // DateTime
            'DateTime' => [
                \DateTime::createFromFormat('Y-m-d H:i:s', '2018-11-15 10:20:30', new \DateTimeZone('UTC')),
                'DateTime { 2018-11-15T10:20:30+00:00 }',
            ],
            'DateTimeImmutable' => [
                \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-11-15 10:20:30', new \DateTimeZone('UTC')),
                'DateTimeImmutable { 2018-11-15T10:20:30+00:00 }',
            ],
            // Stringable
            '__toString' => [new StringableEntity(), 'MF\Stringify\Fixture\StringableEntity { I\'m stringable. }'],
            'toString' => [new Entity(), 'MF\Stringify\Fixture\Entity { I\'m entity. }'],
            // exception
            'exception' => [
                new SomeException('message', 42),
                'MF\\Stringify\\Fixture\\SomeException { "message", 42, ' . __DIR__ . '/StringifyTest.php #' . (__LINE__ - 1) . ' }',
            ],
            // json
            'json' => [
                new Json(['firstName' => 'Peter', 'surname' => 'Parker']),
                'MF\\Stringify\\Fixture\\Json {"firstName":"Peter","surname":"Parker"}',
            ],
            'long json' => [
                new Json([
                    'firstName' => 'Peter',
                    'surname' => 'Parker',
                    'address' => ['city' => 'New York'],
                    'alterego' => 'Spider-Man',
                    'superpower' => 'spider-senses',
                ]),
                'MF\Stringify\Fixture\Json {"firstName":"Peter","surname":"Parker","address":{"city":"New York"},"alterego":"Spider-Man","sup...}',
            ],
            'long array values' => [
                [
                    'key' => 'key for value',
                    'value' => self::longString(100, 'abc'),
                ],
                sprintf('["key" => "key for value", "value" => "%s...]', self::longString(59)),
            ],
        ];
    }

    /** @dataProvider provideLongOutput */
    public function testShouldStringifyValueButNotShrinkLongOutput(mixed $value, string $expected): void
    {
        $output = Stringify::stringify($value);

        $this->assertSame($expected, $output);
    }

    public static function provideLongOutput(): array
    {
        return [
            // value, expected
            'long json' => [
                new Json([
                    'firstName' => 'Peter',
                    'surname' => 'Parker',
                    'address' => ['city' => 'New York'],
                    'alterego' => 'Spider-Man',
                    'superpower' => 'spider-senses',
                ]),
                'MF\Stringify\Fixture\Json {"firstName":"Peter","surname":"Parker","address":{"city":"New York"},"alterego":"Spider-Man","superpower":"spider-senses"}',
            ],
            'long array values' => [
                [
                    'key' => 'key for value',
                    'value' => self::longString(100, 'abc'),
                ],
                sprintf('["key" => "key for value", "value" => "%s"]', self::longString(100, 'abc')),
            ],
        ];
    }

    /** @dataProvider provideShrinkedValue */
    public function testShouldStringifyValueViaFunction(mixed $value, string $expected): void
    {
        $result = stringify($value, true);

        $this->assertSame($expected, $result);
    }

    /** @dataProvider provideLongOutput */
    public function testShouldStringifyLongValueViaFunction(mixed $value, string $expected): void
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

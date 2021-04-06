<?php declare(strict_types=1);

namespace MF\Stringify;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    protected function longString(int $length, string $suffix = ''): string
    {
        $string = '';
        for ($i = 1; $i <= $length; $i++) {
            $string .= $i % 10;
        }

        return $string . $suffix;
    }
}

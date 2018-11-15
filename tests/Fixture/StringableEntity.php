<?php declare(strict_types=1);

namespace MF\Stringify\Fixture;

class StringableEntity
{
    public function __toString(): string
    {
        return 'I\'m stringable.';
    }
}

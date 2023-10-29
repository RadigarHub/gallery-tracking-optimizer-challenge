<?php

declare(strict_types=1);

namespace App\Images\Domain\ValueObject;

class EventWeight extends NonNegativeFloat
{
    protected function message(): string
    {
        return 'Weight cannot be smaller than 0';
    }
}

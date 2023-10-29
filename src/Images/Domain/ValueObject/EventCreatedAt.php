<?php

declare(strict_types=1);

namespace App\Images\Domain\ValueObject;

class EventCreatedAt extends PriorCurrentDateTime
{
    protected function message(): string
    {
        return 'CreatedAt datetime must be prior to the current datetime';
    }
}

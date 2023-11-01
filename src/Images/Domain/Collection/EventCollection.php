<?php

declare(strict_types=1);

namespace App\Images\Domain\Collection;

use App\Images\Domain\Entity\Event;

class EventCollection extends Collection
{
    protected function type(): string
    {
        return Event::class;
    }
}

<?php

declare(strict_types=1);

namespace App\Images\Domain\Service;

interface UuidGenerator
{
    public function generate(): string;
}

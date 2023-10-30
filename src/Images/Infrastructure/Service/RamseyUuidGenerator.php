<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Service;

use App\Images\Domain\Service\UuidGenerator;
use Ramsey\Uuid\Uuid;

class RamseyUuidGenerator implements UuidGenerator
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}

<?php

declare(strict_types=1);

namespace App\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;

class ImageCreatedAt
{
    public function __construct(private readonly \DateTimeImmutable $value)
    {
        $this->ensureIsValidDateTime($value);
    }

    private function ensureIsValidDateTime(\DateTimeImmutable $dateTime): void
    {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        if ($dateTime > $now) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The creation datetime [%s] cannot be later than the current datetime',
                    $dateTime->format('Y-m-d\TH:i:s\Z')
                )
            );
        }
    }

    public function getValue(): \DateTimeImmutable
    {
        return $this->value;
    }

    public function equalsTo(ImageCreatedAt $imageCreatedAt): bool
    {
        return $this->getValue() === $imageCreatedAt->getValue();
    }
}

<?php

declare(strict_types=1);

namespace App\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;

abstract class PriorCurrentDateTime
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
                    '[%s] %s',
                    $dateTime->format('Y-m-d\TH:i:s\Z'),
                    $this->message()
                )
            );
        }
    }

    abstract protected function message(): string;

    public function getValue(): \DateTimeImmutable
    {
        return $this->value;
    }

    public function equalsTo(PriorCurrentDateTime $priorCurrentDateTime): bool
    {
        return $this->getValue() === $priorCurrentDateTime->getValue();
    }
}

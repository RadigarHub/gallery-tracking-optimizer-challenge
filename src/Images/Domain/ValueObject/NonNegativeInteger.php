<?php

declare(strict_types=1);

namespace App\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;

abstract class NonNegativeInteger
{
    public function __construct(private readonly int $value)
    {
        $this->ensureIsValidInteger($value);
    }

    protected function ensureIsValidInteger(int $integer): void
    {
        if ($integer < 0) {
            throw new InvalidArgumentException($this->message());
        }
    }

    abstract protected function message(): string;

    public function getValue(): int
    {
        return $this->value;
    }

    public function equalsTo(NonNegativeInteger $nonNegativeInteger): bool
    {
        return $this->getValue() === $nonNegativeInteger->getValue();
    }
}

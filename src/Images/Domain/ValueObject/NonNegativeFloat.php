<?php

declare(strict_types=1);

namespace App\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;

abstract class NonNegativeFloat
{
    public function __construct(private readonly float $value)
    {
        $this->ensureIsValidFloat($value);
    }

    protected function ensureIsValidFloat(float $float): void
    {
        if ($float < 0) {
            throw new InvalidArgumentException($this->message());
        }
    }

    abstract protected function message(): string;

    public function getValue(): float
    {
        return $this->value;
    }

    public function equalsTo(NonNegativeFloat $nonNegativeFloat): bool
    {
        return $this->getValue() === $nonNegativeFloat->getValue();
    }
}

<?php

declare(strict_types=1);

namespace App\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;

class ImageName
{
    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 50;

    public function __construct(private readonly string $value)
    {
        $this->ensureIsValidName($value);
    }

    private function ensureIsValidName(string $name): void
    {
        if (\strlen($name) < self::MIN_LENGTH || \strlen($name) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                \sprintf(
                    'The name [%s] is not well formed, its length must be between %s and %s characters',
                    $name,
                    self::MIN_LENGTH,
                    self::MAX_LENGTH
                )
            );
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equalsTo(ImageName $imageName): bool
    {
        return $this->getValue() === $imageName->getValue();
    }
}

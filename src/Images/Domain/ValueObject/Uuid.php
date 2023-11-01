<?php

declare(strict_types=1);

namespace App\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;

abstract class Uuid
{
    private const VALID_PATTERN_UUID_V4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';

    public function __construct(private readonly string $value)
    {
        $this->ensureIsValidUuid($value);
    }

    private function ensureIsValidUuid(string $uuid): void
    {
        if (!preg_match(self::VALID_PATTERN_UUID_V4, $uuid)) {
            throw new InvalidArgumentException(\sprintf('The uuid [%s] is not well formed', $uuid));
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equalsTo(Uuid $uuid): bool
    {
        return $this->getValue() === $uuid->getValue();
    }
}

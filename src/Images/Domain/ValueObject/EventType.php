<?php

declare(strict_types=1);

namespace App\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;

class EventType
{
    public const TYPE_VIEW = 'view';
    public const TYPE_CLICK = 'click';

    private const VALID_TYPES = [
        self::TYPE_VIEW,
        self::TYPE_CLICK,
    ];

    private const TYPES_WEIGHT = [
        self::TYPE_VIEW => 0.3,
        self::TYPE_CLICK => 0.7,
    ];

    public function __construct(private readonly string $value)
    {
        $this->ensureIsValidType($value);
    }

    private function ensureIsValidType(string $type): void
    {
        if (!in_array($type, self::VALID_TYPES)) {
            throw new InvalidArgumentException(\sprintf(
                'The type [%s] is invalid. Valid types [%s, %s]',
                $type,
                self::TYPE_VIEW,
                self::TYPE_CLICK
            ));
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getWeight(): float
    {
        return self::TYPES_WEIGHT[$this->getValue()];
    }

    public function equalsTo(EventType $eventType): bool
    {
        return $this->getValue() === $eventType->getValue();
    }
}

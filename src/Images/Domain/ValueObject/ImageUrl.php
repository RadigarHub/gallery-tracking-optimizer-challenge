<?php

declare(strict_types=1);

namespace App\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;

class ImageUrl
{
    public function __construct(private readonly string $value)
    {
        $this->ensureIsValidUrl($value);
    }

    private function ensureIsValidUrl(string $url): void
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException(\sprintf('The url [%s] is not well formed', $url));
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equalsTo(ImageUrl $imageUrl): bool
    {
        return $this->getValue() === $imageUrl->getValue();
    }
}

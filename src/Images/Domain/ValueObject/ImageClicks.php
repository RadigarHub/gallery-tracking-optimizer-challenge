<?php

declare(strict_types=1);

namespace App\Images\Domain\ValueObject;

class ImageClicks extends NonNegativeInteger
{
    public function addClicks(int $clicks): self
    {
        $this->ensureIsValidInteger($clicks);

        return new ImageClicks($this->getValue() + $clicks);
    }

    protected function message(): string
    {
        return 'Clicks cannot be smaller than 0';
    }
}

<?php

declare(strict_types=1);

namespace App\Images\Domain\ValueObject;

class ImageWeight extends NonNegativeFloat
{
    public function addWeight(float $weight): self
    {
        $this->ensureIsValidFloat($weight);

        return new ImageWeight($this->getValue() + $weight);
    }

    protected function message(): string
    {
        return 'Weight cannot be smaller than 0';
    }
}

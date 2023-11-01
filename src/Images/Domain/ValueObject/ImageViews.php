<?php

declare(strict_types=1);

namespace App\Images\Domain\ValueObject;

class ImageViews extends NonNegativeInteger
{
    public function addViews(int $views): self
    {
        $this->ensureIsValidInteger($views);

        return new ImageViews($this->getValue() + $views);
    }

    protected function message(): string
    {
        return 'Views cannot be smaller than 0';
    }
}

<?php

declare(strict_types=1);

namespace App\Images\Domain\Collection;

use App\Images\Domain\Entity\Image;

class ImageCollection extends Collection
{
    protected function type(): string
    {
        return Image::class;
    }
}

<?php

declare(strict_types=1);

namespace App\Images\Domain\Repository;

use App\Images\Domain\Collection\ImageCollection;
use App\Images\Domain\ValueObject\ImageId;

interface ImageRepository
{
    public function saveMultiple(ImageCollection $imageCollection);
    public function exist(ImageId $ImageId): bool;
}

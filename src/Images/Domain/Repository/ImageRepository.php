<?php

declare(strict_types=1);

namespace App\Images\Domain\Repository;

use App\Images\Domain\Collection\ImageCollection;
use App\Images\Domain\Entity\Image;
use App\Images\Domain\ValueObject\ImageId;

interface ImageRepository
{
    public function insert(Image $image): void;
    public function update(Image $image): void;
    public function insertMultiple(ImageCollection $imageCollection): void;
    public function exist(ImageId $ImageId): bool;
    public function findOneByIdOrFail(ImageId $imageId): Image;
}

<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Persistence\Doctrine;

use App\Images\Domain\Collection\ImageCollection;
use App\Images\Domain\Entity\Image;
use App\Images\Domain\Repository\ImageRepository;
use App\Images\Domain\ValueObject\ImageId;
use App\Images\Infrastructure\Persistence\Doctrine\Entity\DoctrineImage;

class DoctrineImageRepository extends DoctrineRepository implements ImageRepository
{
    protected function entityClassName(): string
    {
        return DoctrineImage::class;
    }

    public function saveMultiple(ImageCollection $imageCollection): void
    {
        foreach ($imageCollection as $image) {
            $this->entityManager()->persist($this->toDoctrine($image));
        }
        $this->entityManager()->flush();
    }

    private function toDoctrine(Image $image): DoctrineImage
    {
        return new DoctrineImage(
            $image->getId()->getValue(),
            $image->getName()->getValue(),
            $image->getUrl()->getValue(),
            $image->getCreatedAt()->getValue()
        );
    }

    public function exist(ImageId $ImageId): bool
    {
        return null !== $this->repository()->find($ImageId->getValue());
    }
}

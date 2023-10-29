<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Persistence\Doctrine;

use App\Images\Domain\Collection\ImageCollection;
use App\Images\Domain\Entity\Image;
use App\Images\Domain\Exception\ResourceNotFoundException;
use App\Images\Domain\Repository\ImageRepository;
use App\Images\Domain\ValueObject\ImageId;
use App\Images\Infrastructure\Persistence\Doctrine\Entity\DoctrineImage;

class DoctrineImageRepository extends DoctrineRepository implements ImageRepository
{
    protected function entityClassName(): string
    {
        return DoctrineImage::class;
    }

    public function insert(Image $image): void
    {
        $doctrineImage = DoctrineImage::newFromDomain($image);
        $this->entityManager()->persist($doctrineImage);
        $this->entityManager()->flush($doctrineImage);
    }

    public function update(Image $image): void
    {
        /** @var DoctrineImage $doctrineImage */
        $doctrineImage = $this->repository()->find($image->getId()->getValue());
        if (null === $doctrineImage) {
            throw new ResourceNotFoundException('There are no image with the specified ID');
        }

        $updatedDoctrineImage = $doctrineImage->updateFromDomain($image);
        $this->entityManager()->persist($updatedDoctrineImage);
        $this->entityManager()->flush($updatedDoctrineImage);
    }

    public function insertMultiple(ImageCollection $imageCollection): void
    {
        foreach ($imageCollection as $image) {
            $this->entityManager()->persist(DoctrineImage::newFromDomain($image));
        }
        $this->entityManager()->flush();
    }

    public function exist(ImageId $ImageId): bool
    {
        return null !== $this->repository()->find($ImageId->getValue());
    }

    public function findOneByIdOrFail(ImageId $imageId): Image
    {
        /** @var DoctrineImage $doctrineImage */
        $doctrineImage = $this->repository()->find($imageId->getValue());
        if (null === $doctrineImage) {
            throw new ResourceNotFoundException('There are no image with the specified ID');
        }

        return $doctrineImage->toDomain();
    }
}

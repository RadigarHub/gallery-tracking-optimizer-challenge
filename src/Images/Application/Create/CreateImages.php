<?php

declare(strict_types=1);

namespace App\Images\Application\Create;

use App\Images\Application\Create\DTO\CreateImagesDTO;
use App\Images\Application\Create\DTO\ImageDTO;
use App\Images\Domain\Collection\ImageCollection;
use App\Images\Domain\Entity\Image;
use App\Images\Domain\Repository\ImageRepository;
use App\Images\Domain\ValueObject\ImageCreatedAt;
use App\Images\Domain\ValueObject\ImageId;
use App\Images\Domain\ValueObject\ImageName;
use App\Images\Domain\ValueObject\ImageUrl;

class CreateImages
{
    private ImageCollection $imageCollection;

    public function __construct(private readonly ImageRepository $repository)
    {
        $this->imageCollection = ImageCollection::init();
    }

    public function handle(CreateImagesDTO $dto): void
    {
        $this->addImagesToCollection(array_unique($dto->getImages()));
        $this->repository->insertMultiple($this->imageCollection);
    }

    private function addImagesToCollection(array $images): void
    {
        foreach ($images as $imageDTO) {
            $this->addImageToCollectionIfNotExist($imageDTO);
        }
    }

    private function addImageToCollectionIfNotExist(ImageDTO $imageDTO): void
    {
        $id = new ImageId($imageDTO->getId());
        if ($this->repository->exist($id)) {
            return;
        }

        $image = Image::create(
            $id,
            new ImageName($imageDTO->getName()),
            new ImageUrl($imageDTO->getUrl()),
            new ImageCreatedAt(new \DateTimeImmutable($imageDTO->getCreatedAt()))
        );
        $this->imageCollection->add($image);
    }
}

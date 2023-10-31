<?php

declare(strict_types=1);

namespace App\Images\Application\Find\DTO;

use App\Images\Domain\Collection\ImageCollection;
use App\Images\Domain\Entity\Image;

class FindImagesDTO
{
    private readonly array $images;

    public function __construct(ImageCollection $imageCollection)
    {
        $gridPosition = 0;
        $images = \array_map(function (Image $image) use (&$gridPosition): array {
            $gridPosition++;
            return [
                'id' => $image->getId()->getValue(),
                'name' => $image->getName()->getValue(),
                'weight' => $image->getWeight()->getValue(),
                'gridPosition' => $gridPosition,
                'url' => $image->getUrl()->getValue(),
                'events' => [
                    'views' => $image->getViews()->getValue(),
                    'clicks' => $image->getClicks()->getValue(),
                ],
            ];
        }, $imageCollection->getItems());

        $this->images = $images;
    }

    public function getImages(): array
    {
        return $this->images;
    }
}

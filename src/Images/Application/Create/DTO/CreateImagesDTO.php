<?php

declare(strict_types=1);

namespace App\Images\Application\Create\DTO;

class CreateImagesDTO
{
    /**
     * @var ImageDTO[]
     */
    private array $images = [];

    public function __construct(array $images)
    {
        $this->parseImages($images);
    }

    private function parseImages(array $images): void
    {
        foreach ($images as $image) {
            $this->images[] = new ImageDTO(
                $image['id'],
                $image['name'],
                $image['url'],
                $image['created_at']
            );
        }
    }

    /**
     * @return ImageDTO[]
     */
    public function getImages(): array
    {
        return $this->images;
    }
}

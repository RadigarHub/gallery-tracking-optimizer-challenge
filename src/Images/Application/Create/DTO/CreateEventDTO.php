<?php

declare(strict_types=1);

namespace App\Images\Application\Create\DTO;

class CreateEventDTO
{
    public function __construct(
        private readonly string $imageId,
        private readonly string $type,
        private readonly string $createdAt
    ) {
    }

    public function getImageId(): string
    {
        return $this->imageId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}

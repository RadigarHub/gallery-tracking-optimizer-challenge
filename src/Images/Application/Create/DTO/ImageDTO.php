<?php

declare(strict_types=1);

namespace App\Images\Application\Create\DTO;

class ImageDTO
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $url,
        private readonly string $createdAt
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function __toString(): string
    {
        return $this->getId();
    }
}

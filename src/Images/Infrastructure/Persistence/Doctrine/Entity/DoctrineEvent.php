<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Persistence\Doctrine\Entity;

class DoctrineEvent
{
    public function __construct(
        private readonly string $id,
        private readonly DoctrineImage $image,
        private readonly string $type,
        private readonly \DateTimeInterface $createdAt
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getImage(): DoctrineImage
    {
        return $this->image;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}

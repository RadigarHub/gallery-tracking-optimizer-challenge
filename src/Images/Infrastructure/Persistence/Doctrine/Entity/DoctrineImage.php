<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Persistence\Doctrine\Entity;

class DoctrineImage
{
    public function __construct(
        private readonly string $id,
        private string $name,
        private string $url,
        private readonly \DateTimeInterface $createdAt
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

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}

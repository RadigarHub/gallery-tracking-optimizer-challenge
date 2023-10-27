<?php

declare(strict_types=1);

namespace App\Images\Domain\Entity;

use App\Images\Domain\ValueObject\ImageCreatedAt;
use App\Images\Domain\ValueObject\ImageId;
use App\Images\Domain\ValueObject\ImageName;
use App\Images\Domain\ValueObject\ImageUrl;

class Image
{
    private function __construct(
        private readonly ImageId $id,
        private ImageName $name,
        private ImageUrl $url,
        private readonly ImageCreatedAt $createdAt
    ) {
    }

    public static function create(ImageId $id, ImageName $name, ImageUrl $url, ImageCreatedAt $createdAt): self
    {
        return new self($id, $name, $url, $createdAt);
    }

    public function getId(): ImageId
    {
        return $this->id;
    }

    public function getName(): ImageName
    {
        return $this->name;
    }

    public function getUrl(): ImageUrl
    {
        return $this->url;
    }

    public function getCreatedAt(): ImageCreatedAt
    {
        return $this->createdAt;
    }
}

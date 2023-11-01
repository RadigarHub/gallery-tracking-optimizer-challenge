<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Api\DTO;

use Symfony\Component\HttpFoundation\Request;

class CreateEventRequestDTO implements RequestDTO
{
    private readonly ?string $imageId;
    private readonly ?string $type;
    private readonly ?string $createdAt;

    public function __construct(Request $request)
    {
        $this->imageId = $request->attributes->get('imageId');
        $this->type = $request->request->get('eventType');
        $this->createdAt = $request->request->get('timestamp');
    }

    public function getImageId(): ?string
    {
        return $this->imageId;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }
}

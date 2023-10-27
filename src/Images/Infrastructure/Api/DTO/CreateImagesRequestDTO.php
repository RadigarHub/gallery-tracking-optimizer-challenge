<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Api\DTO;

use Symfony\Component\HttpFoundation\Request;

class CreateImagesRequestDTO implements RequestDTO
{
    private readonly array $images;

    public function __construct(Request $request)
    {
        $this->images = $request->request->all();
    }

    public function getImages(): array
    {
        return $this->images;
    }
}

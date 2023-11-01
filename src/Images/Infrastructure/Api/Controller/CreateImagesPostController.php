<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Api\Controller;

use App\Images\Application\Create\CreateImages;
use App\Images\Application\Create\DTO\CreateImagesDTO;
use App\Images\Domain\Exception\InvalidArgumentException;
use App\Images\Infrastructure\Api\DTO\CreateImagesRequestDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateImagesPostController extends AbstractController
{
    public function __construct(private readonly CreateImages $service)
    {
    }

    public function __invoke(CreateImagesRequestDTO $requestDTO): JsonResponse
    {
        $this->validateRequestDTO($requestDTO);
        $this->service->handle(new CreateImagesDTO($requestDTO->getImages()));

        return new JsonResponse(['message' => 'Images received'], Response::HTTP_OK);
    }

    private function validateRequestDTO(CreateImagesRequestDTO $requestDTO): void
    {
        $images = $requestDTO->getImages();
        if (empty($images)) {
            throw InvalidArgumentException::createFromMessage('The request payload is not well formed');
        }

        foreach ($images as $image) {
            if (!isset($image['id'], $image['name'], $image['url'], $image['created_at'])) {
                throw InvalidArgumentException::createFromMessage('The request payload is not well formed');
            }
        }
    }
}

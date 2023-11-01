<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Api\Controller;

use App\Images\Application\Create\CreateEvent;
use App\Images\Application\Create\DTO\CreateEventDTO;
use App\Images\Domain\Exception\InvalidArgumentException;
use App\Images\Infrastructure\Api\DTO\CreateEventRequestDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateEventPostController extends AbstractController
{
    public function __construct(private readonly CreateEvent $service)
    {
    }

    public function __invoke(CreateEventRequestDTO $requestDTO): JsonResponse
    {
        $this->validateRequestDTO($requestDTO);
        $this->service->handle(
            new CreateEventDTO($requestDTO->getImageId(), $requestDTO->getType(), $requestDTO->getCreatedAt())
        );

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    private function validateRequestDTO(CreateEventRequestDTO $requestDTO): void
    {
        if (null === $requestDTO->getImageId() ||
            null === $requestDTO->getType() ||
            null === $requestDTO->getCreatedAt()
        ) {
            throw InvalidArgumentException::createFromMessage('The request payload is not well formed');
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Api\Controller;

use App\Images\Application\Find\FindImages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class FindImagesGetController extends AbstractController
{
    public function __construct(private readonly FindImages $service)
    {
    }

    public function __invoke(): JsonResponse
    {
        $findImagesDTO = $this->service->handle();

        return new JsonResponse($findImagesDTO->getImages());
    }
}

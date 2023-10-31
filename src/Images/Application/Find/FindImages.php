<?php

declare(strict_types=1);

namespace App\Images\Application\Find;

use App\Images\Application\Find\DTO\FindImagesDTO;
use App\Images\Domain\Repository\ImageRepository;

class FindImages
{
    public function __construct(private readonly ImageRepository $repository)
    {
    }

    public function handle(): FindImagesDTO
    {
        return new FindImagesDTO($this->repository->findAll());
    }
}

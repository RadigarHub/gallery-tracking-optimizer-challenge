<?php

declare(strict_types=1);

namespace App\Images\Application\Create;

use App\Images\Application\Create\DTO\CreateEventDTO;
use App\Images\Domain\Repository\ImageRepository;
use App\Images\Domain\Service\UuidGenerator;
use App\Images\Domain\ValueObject\EventCreatedAt;
use App\Images\Domain\ValueObject\EventId;
use App\Images\Domain\ValueObject\EventType;
use App\Images\Domain\ValueObject\ImageId;

class CreateEvent
{
    public function __construct(private readonly ImageRepository $repository, private readonly UuidGenerator $uuidGenerator)
    {
    }

    public function handle(CreateEventDTO $dto): void
    {
        $image = $this->repository->findOneByIdOrFail(new ImageId($dto->getImageId()));
        $image->addEvent(
            new EventId($this->uuidGenerator->generate()),
            new EventType($dto->getType()),
            new EventCreatedAt(new \DateTimeImmutable($dto->getCreatedAt()))
        );
        $this->repository->update($image);
    }
}

<?php

declare(strict_types=1);

namespace App\Images\Domain\Entity;

use App\Images\Domain\ValueObject\EventCreatedAt;
use App\Images\Domain\ValueObject\EventId;
use App\Images\Domain\ValueObject\EventType;
use App\Images\Domain\ValueObject\EventWeight;

class Event
{
    private function __construct(
        private readonly EventId $id,
        private readonly Image $image,
        private readonly EventType $eventType,
        private readonly EventCreatedAt $eventCreatedAt
    ) {
    }

    public static function create(EventId $id, Image $image, EventType $type, EventCreatedAt $createdAt): self
    {
        return new self($id, $image, $type, $createdAt);
    }

    public function getId(): EventId
    {
        return $this->id;
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    public function getEventType(): EventType
    {
        return $this->eventType;
    }

    public function getEventCreatedAt(): EventCreatedAt
    {
        return $this->eventCreatedAt;
    }

    public function getWeight(): EventWeight
    {
        return new EventWeight($this->eventType->getWeight());
    }

    public function isView(): bool
    {
        return $this->eventType->getValue() === EventType::TYPE_VIEW;
    }

    public function isClick(): bool
    {
        return $this->eventType->getValue() === EventType::TYPE_CLICK;
    }
}

<?php

declare(strict_types=1);

namespace App\Images\Domain\Entity;

use App\Images\Domain\Collection\EventCollection;
use App\Images\Domain\ValueObject\EventCreatedAt;
use App\Images\Domain\ValueObject\EventId;
use App\Images\Domain\ValueObject\EventType;
use App\Images\Domain\ValueObject\ImageClicks;
use App\Images\Domain\ValueObject\ImageCreatedAt;
use App\Images\Domain\ValueObject\ImageId;
use App\Images\Domain\ValueObject\ImageName;
use App\Images\Domain\ValueObject\ImageUrl;
use App\Images\Domain\ValueObject\ImageViews;
use App\Images\Domain\ValueObject\ImageWeight;

class Image
{
    private EventCollection $events;
    private ImageViews $views;
    private ImageClicks $clicks;
    private ImageWeight $weight;

    private function __construct(
        private readonly ImageId $id,
        private ImageName $name,
        private ImageUrl $url,
        private readonly ImageCreatedAt $createdAt,
        ?EventCollection $events,
        ?ImageViews $views,
        ?ImageClicks $clicks,
        ?ImageWeight $weight,
    ) {
        $this->events = $events ?? EventCollection::init();
        $this->views = $views ?? new ImageViews(0);
        $this->clicks = $clicks ?? new imageClicks(0);
        $this->weight = $weight ?? new ImageWeight(0);
    }

    public static function create(
        ImageId $id,
        ImageName $name,
        ImageUrl $url,
        ImageCreatedAt $createdAt,
        ?EventCollection $events = null,
        ?ImageViews $views = null,
        ?ImageClicks $clicks = null,
        ?ImageWeight $weight = null,
    ): self {
        return new self($id, $name, $url, $createdAt, $events, $views, $clicks, $weight);
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

    public function getEvents(): EventCollection
    {
        return $this->events;
    }

    public function addEvent(EventId $id, EventType $type, EventCreatedAt $createdAt): void
    {
        $event = Event::create($id, $this, $type, $createdAt);
        $this->events->add($event);
        $this->calculateEventValues($event);
    }

    private function calculateEventValues(Event $event): void
    {
        if ($event->isView()) {
            $this->views = new ImageViews($this->getViews()->getValue() + 1);
        }
        if ($event->isClick()) {
            $this->clicks = new ImageClicks($this->getClicks()->getValue() + 1);
        }
        $this->weight = new ImageWeight($this->getWeight()->getValue() + $event->getWeight()->getValue());
    }

    public function getViews(): ImageViews
    {
        return $this->views;
    }

    public function getClicks(): ImageClicks
    {
        return $this->clicks;
    }

    public function getWeight(): ImageWeight
    {
        return $this->weight;
    }
}

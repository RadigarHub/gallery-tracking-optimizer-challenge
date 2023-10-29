<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Persistence\Doctrine\Entity;

use App\Images\Domain\Entity\Event;
use App\Images\Domain\Entity\Image;
use App\Images\Domain\ValueObject\ImageClicks;
use App\Images\Domain\ValueObject\ImageCreatedAt;
use App\Images\Domain\ValueObject\ImageId;
use App\Images\Domain\ValueObject\ImageName;
use App\Images\Domain\ValueObject\ImageUrl;
use App\Images\Domain\ValueObject\ImageViews;
use App\Images\Domain\ValueObject\ImageWeight;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class DoctrineImage
{
    public function __construct(
        private readonly string $id,
        private string $name,
        private string $url,
        private readonly \DateTimeInterface $createdAt,
        private Collection $events,
        private int $views,
        private int $clicks,
        private float $weight
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function getClicks(): int
    {
        return $this->clicks;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function addEvent(DoctrineEvent $event): void
    {
        $this->events->add($event);
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    public function setClicks(int $clicks): void
    {
        $this->clicks = $clicks;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    public function toDomain(): Image
    {
        return Image::create(
            new ImageId($this->getId()),
            new ImageName($this->getName()),
            new ImageUrl($this->getUrl()),
            new ImageCreatedAt(\DateTimeImmutable::createFromInterface($this->getCreatedAt())),
            null,
            new ImageViews($this->getViews()),
            new ImageClicks($this->getClicks()),
            new ImageWeight($this->getWeight())
        );
    }

    public static function newFromDomain(Image $image): self
    {
        $doctrineImage = new self(
            $image->getId()->getValue(),
            $image->getName()->getValue(),
            $image->getUrl()->getValue(),
            $image->getCreatedAt()->getValue(),
            new ArrayCollection(),
            $image->getViews()->getValue(),
            $image->getClicks()->getValue(),
            $image->getWeight()->getValue()
        );
        foreach ($image->getEvents()->getItems() as $event) {
            $doctrineImage->addEventFromDomain($event);
        }

        return $doctrineImage;
    }

    private function addEventFromDomain(Event $event): void
    {
        $this->addEvent(
            new DoctrineEvent(
                $event->getId()->getValue(),
                $this,
                $event->getEventType()->getValue(),
                $event->getEventCreatedAt()->getValue()
            )
        );
    }

    public function updateFromDomain(Image $image): self
    {
        $this->setName($image->getName()->getValue());
        $this->setUrl($image->getUrl()->getValue());
        $this->setViews($image->getViews()->getValue());
        $this->setClicks($image->getClicks()->getValue());
        $this->setWeight($image->getWeight()->getValue());
        foreach ($image->getEvents()->getItems() as $event) {
            $this->addEventFromDomain($event);
        }

        return $this;
    }
}

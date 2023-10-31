<?php

declare(strict_types=1);

namespace App\Tests\Unit\Images\Application\Create;

use App\Images\Application\Create\CreateEvent;
use App\Images\Application\Create\DTO\CreateEventDTO;
use App\Images\Domain\Entity\Image;
use App\Images\Domain\Exception\ResourceNotFoundException;
use App\Images\Domain\Repository\ImageRepository;
use App\Images\Domain\Service\UuidGenerator;
use App\Images\Domain\ValueObject\ImageCreatedAt;
use App\Images\Domain\ValueObject\ImageId;
use App\Images\Domain\ValueObject\ImageName;
use App\Images\Domain\ValueObject\ImageUrl;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateEventTest extends TestCase
{
    private const VIEW_EVENT = [
        'imageId' => '29d3cff2-1213-4c62-a684-7df01e5fa493',
        'eventType' => 'view',
        'timestamp' => "2019-08-24T14:15:22Z",
    ];
    private const CLICK_EVENT = [
        'imageId' => '29d3cff2-1213-4c62-a684-7df01e5fa493',
        'eventType' => 'click',
        'timestamp' => "2019-08-24T14:15:22Z",
    ];

    private ImageRepository|MockObject $imageRepository;
    private UuidGenerator|MockObject $uuidGenerator;
    private CreateEvent $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imageRepository = $this->createMock(ImageRepository::class);
        $this->uuidGenerator = $this->createMock(UuidGenerator::class);
        $this->service = new CreateEvent($this->imageRepository, $this->uuidGenerator);
    }

    /** @test */
    public function it_should_create_valid_view_event(): void
    {
        $image = $this->createImage();

        $this->imageRepository
            ->expects($this->once())
            ->method('findOneByIdOrFail')
            ->with($image->getId())
            ->willReturn($image);

        $this->uuidGenerator
            ->expects($this->once())
            ->method('generate')
            ->willReturn('00603033-501d-4384-8f3d-95eefdfd163f');

        $this->imageRepository
            ->expects($this->once())
            ->method('update')
            ->with($image);

        $this->service->handle(
            new CreateEventDTO(
                self::VIEW_EVENT['imageId'], self::VIEW_EVENT['eventType'], self::VIEW_EVENT['timestamp']
            )
        );

        $this->assertCount(1, $image->getEvents());
        $this->assertEquals(1, $image->getViews()->getValue());
        $this->assertEquals(0, $image->getClicks()->getValue());
        $this->assertEquals(0.3, $image->getWeight()->getValue());
    }

    /** @test */
    public function it_should_create_valid_click_event(): void
    {
        $image = $this->createImage();

        $this->imageRepository
            ->expects($this->once())
            ->method('findOneByIdOrFail')
            ->with($image->getId())
            ->willReturn($image);

        $this->uuidGenerator
            ->expects($this->once())
            ->method('generate')
            ->willReturn('00603033-501d-4384-8f3d-95eefdfd163f');

        $this->imageRepository
            ->expects($this->once())
            ->method('update')
            ->with($image);

        $this->service->handle(
            new CreateEventDTO(
                self::CLICK_EVENT['imageId'], self::CLICK_EVENT['eventType'], self::CLICK_EVENT['timestamp']
            )
        );

        $this->assertCount(1, $image->getEvents());
        $this->assertEquals(0, $image->getViews()->getValue());
        $this->assertEquals(1, $image->getClicks()->getValue());
        $this->assertEquals(0.7, $image->getWeight()->getValue());
    }

    /** @test */
    public function it_should_create_valid_view_and_click_events(): void
    {
        $image = $this->createImage();

        $this->imageRepository
            ->expects($this->exactly(2))
            ->method('findOneByIdOrFail')
            ->with($image->getId())
            ->willReturn($image);

        $this->uuidGenerator
            ->expects($this->exactly(2))
            ->method('generate')
            ->willReturn('00603033-501d-4384-8f3d-95eefdfd163f', '00845088-6b3b-4729-af0b-283928a21a52');

        $this->imageRepository
            ->expects($this->exactly(2))
            ->method('update')
            ->with($image);

        $this->service->handle(
            new CreateEventDTO(
                self::VIEW_EVENT['imageId'], self::VIEW_EVENT['eventType'], self::VIEW_EVENT['timestamp']
            )
        );
        $this->service->handle(
            new CreateEventDTO(
                self::CLICK_EVENT['imageId'], self::CLICK_EVENT['eventType'], self::CLICK_EVENT['timestamp']
            )
        );

        $this->assertCount(2, $image->getEvents());
        $this->assertEquals(1, $image->getViews()->getValue());
        $this->assertEquals(1, $image->getClicks()->getValue());
        $this->assertEquals(1, $image->getWeight()->getValue());
    }

    /** @test */
    public function it_should_fail_if_image_does_not_exist(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage('There are no image with the specified ID');

        $image = $this->createImage();
        $this->imageRepository
            ->expects($this->once())
            ->method('findOneByIdOrFail')
            ->with($image->getId())
            ->willThrowException(new ResourceNotFoundException('There are no image with the specified ID'));

        $this->service->handle(
            new CreateEventDTO(
                self::VIEW_EVENT['imageId'], self::VIEW_EVENT['eventType'], self::VIEW_EVENT['timestamp']
            )
        );
    }

    private function createImage(): Image
    {
        return Image::create(
            new ImageId(self::VIEW_EVENT['imageId']),
            new ImageName('Image Test'),
            new ImageUrl('http://test.com'),
            new ImageCreatedAt(new \DateTimeImmutable('yesterday', new \DateTimeZone('UTC')))
        );
    }
}

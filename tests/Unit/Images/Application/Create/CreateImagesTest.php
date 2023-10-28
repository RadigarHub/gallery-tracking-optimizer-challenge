<?php

declare(strict_types=1);

namespace App\Tests\Unit\Images\Application\Create;

use App\Images\Application\Create\CreateImages;
use App\Images\Application\Create\DTO\CreateImagesDTO;
use App\Images\Domain\Collection\ImageCollection;
use App\Images\Domain\Entity\Image;
use App\Images\Domain\Repository\ImageRepository;
use App\Images\Domain\ValueObject\ImageCreatedAt;
use App\Images\Domain\ValueObject\ImageId;
use App\Images\Domain\ValueObject\ImageName;
use App\Images\Domain\ValueObject\ImageUrl;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateImagesTest extends TestCase
{
    private const IMAGES = [
        [
            'id' => '29d3cff2-1213-4c62-a684-7df01e5fa493',
            'name' => 'Aegypius tracheliotus',
            'url' => 'http://dummyimage.com/216x100.png/cc0000/ffffff',
            'created_at' => '2022-01-29T19:18:12Z',
        ],
        [
            'id' => 'c3b9c4b1-e335-4c59-85cb-c4bce4b246fd',
            'name' => 'Larus dominicanus',
            'url' => 'http://dummyimage.com/249x100.png/5fa2dd/ffffff',
            'created_at' => '2021-03-05T00:30:47Z',
        ],
    ];

    private ImageRepository|MockObject $imageRepository;
    private CreateImages $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imageRepository = $this->createMock(ImageRepository::class);
        $this->service = new CreateImages($this->imageRepository);
    }

    /** @test */
    public function it_should_create_valid_images(): void
    {
        $expected1 = new ImageId(self::IMAGES[0]['id']);
        $expected2 = new ImageId(self::IMAGES[1]['id']);
        $this->imageRepository
            ->expects($this->exactly(2))
            ->method('exist')
            ->withConsecutive(
                [$expected1],
                [$expected2]
            )
            ->willReturn(false, false);

        $imageCollection = $this->createImageCollection();
        $this->imageRepository
            ->expects($this->once())
            ->method('saveMultiple')
            ->with($imageCollection)
            ->willReturn(null);

        $this->service->handle(new CreateImagesDTO(self::IMAGES));
    }

    /** @test */
    public function it_should_not_fail_if_there_are_duplicate_images(): void
    {
        $expected1 = new ImageId(self::IMAGES[0]['id']);
        $expected2 = new ImageId(self::IMAGES[1]['id']);
        $this->imageRepository
            ->expects($this->exactly(2))
            ->method('exist')
            ->withConsecutive(
                [$expected1],
                [$expected2],
            )
            ->willReturn(false, false);

        $imageCollection = $this->createImageCollection();
        $this->imageRepository
            ->expects($this->once())
            ->method('saveMultiple')
            ->with($imageCollection)
            ->willReturn(null);

        $createImagesDTO = new CreateImagesDTO(array_merge(self::IMAGES, [self::IMAGES[0]]));
        $this->service->handle($createImagesDTO);
    }

    /** @test */
    public function it_should_not_fail_if_images_already_exist(): void
    {
        $expected = new ImageId(self::IMAGES[0]['id']);
        $this->imageRepository
            ->expects($this->once())
            ->method('exist')
            ->with($expected)
            ->willReturn(true);

        $this->imageRepository
            ->expects($this->once())
            ->method('saveMultiple')
            ->with(ImageCollection::init());

        $this->service->handle(new CreateImagesDTO([self::IMAGES[0]]));
    }

    private function createImageCollection(): ImageCollection
    {
        $imageCollection = ImageCollection::init();
        foreach (self::IMAGES as $image) {
            $imageCollection->add(
                Image::create(
                    new ImageId($image['id']),
                    new ImageName($image['name']),
                    new ImageUrl($image['url']),
                    new ImageCreatedAt(new \DateTimeImmutable($image['created_at']))
                )
            );
        }

        return $imageCollection;
    }
}

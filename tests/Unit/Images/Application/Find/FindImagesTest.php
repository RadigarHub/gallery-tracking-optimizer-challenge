<?php

declare(strict_types=1);

namespace App\Tests\Unit\Images\Application\Find;

use App\Images\Application\Find\DTO\FindImagesDTO;
use App\Images\Application\Find\FindImages;
use App\Images\Domain\Repository\ImageRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FindImagesTest extends TestCase
{
    private ImageRepository|MockObject $imageRepository;
    private FindImages $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imageRepository = $this->createMock(ImageRepository::class);
        $this->service = new FindImages($this->imageRepository);
    }

    /** @test */
    public function it_should_find_all_images(): void
    {
        $this->imageRepository
            ->expects($this->once())
            ->method('findAll');

        $findImagesDTO = $this->service->handle();

        self::assertEquals(FindImagesDTO::class, get_class($findImagesDTO));
    }
}

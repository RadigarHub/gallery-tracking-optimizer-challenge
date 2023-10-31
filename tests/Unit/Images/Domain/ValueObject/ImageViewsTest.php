<?php

declare(strict_types=1);

namespace App\Tests\Unit\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;
use App\Images\Domain\ValueObject\ImageViews;
use PHPUnit\Framework\TestCase;

class ImageViewsTest extends TestCase
{
    /** @test */
    public function create_valid_zero(): void
    {
        $validViews = 0;
        $imageViews = new ImageViews($validViews);

        $this->assertInstanceOf(ImageViews::class, $imageViews);
        $this->assertEquals($validViews, $imageViews->getValue());
    }

    /** @test */
    public function create_valid_positive(): void
    {
        $validViews = 1;
        $imageViews = new ImageViews($validViews);

        $this->assertInstanceOf(ImageViews::class, $imageViews);
        $this->assertEquals($validViews, $imageViews->getValue());
    }

    /** @test */
    public function create_invalid_negative(): void
    {
        $invalidViews = -1;
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Views cannot be smaller than 0');

        new ImageViews($invalidViews);
    }

    /** @test */
    public function add_views(): void
    {
        $imageViews1 = new ImageViews(1);
        $imageViews2 = $imageViews1->addViews(1);

        $this->assertEquals(2, $imageViews2->getValue());
    }

    /** @test */
    public function compare(): void
    {
        $validViews = 1;
        $imageViews1 = new ImageViews($validViews);
        $imageViews2 = new ImageViews($validViews);

        $this->assertTrue($imageViews1->equalsTo($imageViews2));
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;
use App\Images\Domain\ValueObject\ImageClicks;
use PHPUnit\Framework\TestCase;

class ImageClicksTest extends TestCase
{
    /** @test */
    public function create_valid_zero(): void
    {
        $validClicks = 0;
        $imageClicks = new ImageClicks($validClicks);

        $this->assertInstanceOf(ImageClicks::class, $imageClicks);
        $this->assertEquals($validClicks, $imageClicks->getValue());
    }

    /** @test */
    public function create_valid_positive(): void
    {
        $validClicks = 1;
        $imageClicks = new ImageClicks($validClicks);

        $this->assertInstanceOf(ImageClicks::class, $imageClicks);
        $this->assertEquals($validClicks, $imageClicks->getValue());
    }

    /** @test */
    public function create_invalid_negative(): void
    {
        $invalidViews = -1;
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Clicks cannot be smaller than 0');

        new ImageClicks($invalidViews);
    }

    /** @test */
    public function add_clicks(): void
    {
        $imageClicks1 = new ImageClicks(1);
        $imageClicks2 = $imageClicks1->addClicks(1);

        $this->assertEquals(2, $imageClicks2->getValue());
    }

    /** @test */
    public function compare(): void
    {
        $validClicks = 1;
        $imageClicks1 = new ImageClicks($validClicks);
        $imageClicks2 = new ImageClicks($validClicks);

        $this->assertTrue($imageClicks1->equalsTo($imageClicks2));
    }
}

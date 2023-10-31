<?php

declare(strict_types=1);

namespace App\Tests\Unit\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;
use App\Images\Domain\ValueObject\ImageWeight;
use PHPUnit\Framework\TestCase;

class ImageWeightTest extends TestCase
{
    /** @test */
    public function create_valid_zero(): void
    {
        $validWeight = 0.0;
        $imageWeight = new ImageWeight($validWeight);

        $this->assertInstanceOf(ImageWeight::class, $imageWeight);
        $this->assertEquals($validWeight, $imageWeight->getValue());
    }

    /** @test */
    public function create_valid_positive(): void
    {
        $validWeight = 1.0;
        $imageWeight = new ImageWeight($validWeight);

        $this->assertInstanceOf(ImageWeight::class, $imageWeight);
        $this->assertEquals($validWeight, $imageWeight->getValue());
    }

    /** @test */
    public function create_invalid_negative(): void
    {
        $invalidViews = -1.0;
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Weight cannot be smaller than 0');

        new ImageWeight($invalidViews);
    }

    /** @test */
    public function add_weight(): void
    {
        $imageWeight1 = new ImageWeight(1.0);
        $imageWeight2 = $imageWeight1->addWeight(1.0);

        $this->assertEquals(2.0, $imageWeight2->getValue());
    }

    /** @test */
    public function compare(): void
    {
        $validWeight = 1.0;
        $imageWeight1 = new ImageWeight($validWeight);
        $imageWeight2 = new ImageWeight($validWeight);

        $this->assertTrue($imageWeight1->equalsTo($imageWeight2));
    }
}

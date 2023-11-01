<?php

declare(strict_types=1);

namespace App\Tests\Unit\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;
use App\Images\Domain\ValueObject\ImageUrl;
use PHPUnit\Framework\TestCase;

class ImageUrlTest extends TestCase
{
    /** @test */
    public function create_valid(): void
    {
        $validUrl = 'http://test.com';
        $imageUrl = new ImageUrl($validUrl);

        $this->assertInstanceOf(ImageUrl::class, $imageUrl);
        $this->assertEquals($validUrl, $imageUrl->getValue());
    }

    /** @test */
    public function create_invalid(): void
    {
        $invalidUrl = 'invalidUrl';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf('The url [%s] is not well formed', $invalidUrl));

        new ImageUrl($invalidUrl);
    }

    /** @test */
    public function compare(): void
    {
        $validUrl = 'http://test.com';
        $imageUrl1 = new ImageUrl($validUrl);
        $imageUrl2 = new ImageUrl($validUrl);

        $this->assertTrue($imageUrl1->equalsTo($imageUrl2));
    }
}

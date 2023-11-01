<?php

declare(strict_types=1);

namespace App\Tests\Unit\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;
use App\Images\Domain\ValueObject\ImageId;
use PHPUnit\Framework\TestCase;

class ImageIdTest extends TestCase
{
    /** @test */
    public function create_valid(): void
    {
        $validUuid = '29d3cff2-1213-4c62-a684-7df01e5fa493';
        $imageId = new ImageId($validUuid);

        $this->assertInstanceOf(ImageId::class, $imageId);
        $this->assertEquals($validUuid, $imageId->getValue());
    }

    /** @test */
    public function create_invalid(): void
    {
        $invalidUuid = '29d3cff2';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf('The uuid [%s] is not well formed', $invalidUuid));

        new ImageId($invalidUuid);
    }

    /** @test */
    public function compare(): void
    {
        $validUuid = '29d3cff2-1213-4c62-a684-7df01e5fa493';
        $imageId1 = new ImageId($validUuid);
        $imageId2 = new ImageId($validUuid);

        $this->assertTrue($imageId1->equalsTo($imageId2));
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;
use App\Images\Domain\ValueObject\ImageCreatedAt;
use PHPUnit\Framework\TestCase;

class ImageCreatedAtTest extends TestCase
{
    /** @test */
    public function create_valid(): void
    {
        $validDateTime = new \DateTimeImmutable('yesterday', new \DateTimeZone('UTC'));
        $imageCreatedAt = new ImageCreatedAt($validDateTime);

        $this->assertInstanceOf(ImageCreatedAt::class, $imageCreatedAt);
        $this->assertEquals($validDateTime, $imageCreatedAt->getValue());
    }

    /** @test */
    public function create_invalid(): void
    {
        $invalidDateTime = new \DateTimeImmutable('tomorrow', new \DateTimeZone('UTC'));
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            \sprintf(
                'The creation datetime [%s] cannot be later than the current datetime',
                $invalidDateTime->format('Y-m-d\TH:i:s\Z')
            )
        );

        new ImageCreatedAt($invalidDateTime);
    }

    /** @test */
    public function compare(): void
    {
        $validDateTime = new \DateTimeImmutable('yesterday', new \DateTimeZone('UTC'));
        $imageCreatedAt1 = new ImageCreatedAt($validDateTime);
        $imageCreatedAt2 = new ImageCreatedAt($validDateTime);

        $this->assertTrue($imageCreatedAt1->equalsTo($imageCreatedAt2));
    }
}

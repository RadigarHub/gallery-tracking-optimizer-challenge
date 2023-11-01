<?php

declare(strict_types=1);

namespace App\Tests\Unit\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;
use App\Images\Domain\ValueObject\ImageName;
use PHPUnit\Framework\TestCase;

class ImageNameTest extends TestCase
{
    /** @test */
    public function create_valid(): void
    {
        $validName = 'Test image';
        $imageName = new ImageName($validName);

        $this->assertInstanceOf(ImageName::class, $imageName);
        $this->assertEquals($validName, $imageName->getValue());
    }

    /** @test */
    public function create_invalid_less_than_two_characters(): void
    {
        $invalidName = 'A';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf('The name [%s] is not well formed, its length must be between 2 and 50 characters', $invalidName));

        new ImageName($invalidName);
    }

    /** @test */
    public function create_invalid_greater_than_fifty_characters(): void
    {
        $invalidName = \str_repeat('A', 51);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf('The name [%s] is not well formed, its length must be between 2 and 50 characters', $invalidName));

        new ImageName($invalidName);
    }

    /** @test */
    public function compare(): void
    {
        $validName = 'Test image';
        $imageName1 = new ImageName($validName);
        $imageName2 = new ImageName($validName);

        $this->assertTrue($imageName1->equalsTo($imageName2));
    }
}

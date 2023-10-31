<?php

declare(strict_types=1);

namespace App\Tests\Unit\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;
use App\Images\Domain\ValueObject\EventWeight;
use PHPUnit\Framework\TestCase;

class EventWeightTest extends TestCase
{
    /** @test */
    public function create_valid_zero(): void
    {
        $validWeight = 0.0;
        $eventWeight = new EventWeight($validWeight);

        $this->assertInstanceOf(EventWeight::class, $eventWeight);
        $this->assertEquals($validWeight, $eventWeight->getValue());
    }

    /** @test */
    public function create_valid_positive(): void
    {
        $validWeight = 1.0;
        $eventWeight = new EventWeight($validWeight);

        $this->assertInstanceOf(EventWeight::class, $eventWeight);
        $this->assertEquals($validWeight, $eventWeight->getValue());
    }

    /** @test */
    public function create_invalid_negative(): void
    {
        $invalidViews = -1.0;
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Weight cannot be smaller than 0');

        new EventWeight($invalidViews);
    }

    /** @test */
    public function compare(): void
    {
        $validWeight = 1.0;
        $eventWeight1 = new EventWeight($validWeight);
        $eventWeight2 = new EventWeight($validWeight);

        $this->assertTrue($eventWeight1->equalsTo($eventWeight2));
    }
}

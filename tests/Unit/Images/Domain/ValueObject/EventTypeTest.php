<?php

declare(strict_types=1);

namespace App\Tests\Unit\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;
use App\Images\Domain\ValueObject\EventType;
use PHPUnit\Framework\TestCase;

class EventTypeTest extends TestCase
{
    /** @test */
    public function create_valid_type_view(): void
    {
        $validType = 'view';
        $eventType = new EventType($validType);

        $this->assertInstanceOf(EventType::class, $eventType);
        $this->assertEquals($validType, $eventType->getValue());
        $this->assertEquals(0.3, $eventType->getWeight());
    }

    /** @test */
    public function create_valid_type_click(): void
    {
        $validType = 'click';
        $eventType = new EventType($validType);

        $this->assertInstanceOf(EventType::class, $eventType);
        $this->assertEquals($validType, $eventType->getValue());
        $this->assertEquals(0.7, $eventType->getWeight());
    }

    /** @test */
    public function create_invalid(): void
    {
        $invalidType = 'invalid';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            \sprintf(
                'The type [%s] is invalid. Valid types [%s, %s]',
                $invalidType,
                EventType::TYPE_VIEW,
                EventType::TYPE_CLICK
            )
        );

        new EventType($invalidType);
    }

    /** @test */
    public function compare(): void
    {
        $validType = 'view';
        $eventType1 = new EventType($validType);
        $eventType2 = new EventType($validType);

        $this->assertTrue($eventType1->equalsTo($eventType2));
    }
}

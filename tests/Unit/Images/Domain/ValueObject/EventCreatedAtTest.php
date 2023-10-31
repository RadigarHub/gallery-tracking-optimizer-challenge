<?php

declare(strict_types=1);

namespace App\Tests\Unit\Images\Domain\ValueObject;

use App\Images\Domain\Exception\InvalidArgumentException;
use App\Images\Domain\ValueObject\EventCreatedAt;
use PHPUnit\Framework\TestCase;

class EventCreatedAtTest extends TestCase
{
    /** @test */
    public function create_valid(): void
    {
        $validDateTime = new \DateTimeImmutable('yesterday', new \DateTimeZone('UTC'));
        $eventCreatedAt = new EventCreatedAt($validDateTime);

        $this->assertInstanceOf(EventCreatedAt::class, $eventCreatedAt);
        $this->assertEquals($validDateTime, $eventCreatedAt->getValue());
    }

    /** @test */
    public function create_invalid(): void
    {
        $invalidDateTime = new \DateTimeImmutable('tomorrow', new \DateTimeZone('UTC'));
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            \sprintf(
                '[%s] CreatedAt datetime must be prior to the current datetime',
                $invalidDateTime->format('Y-m-d\TH:i:s\Z')
            )
        );

        new EventCreatedAt($invalidDateTime);
    }

    /** @test */
    public function compare(): void
    {
        $validDateTime = new \DateTimeImmutable('yesterday', new \DateTimeZone('UTC'));
        $eventCreatedAt1 = new EventCreatedAt($validDateTime);
        $eventCreatedAt2 = new EventCreatedAt($validDateTime);

        $this->assertTrue($eventCreatedAt1->equalsTo($eventCreatedAt2));
    }
}

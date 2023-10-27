<?php

declare(strict_types=1);

namespace App\Images\Domain\Collection;

use App\Images\Domain\Exception\InvalidArgumentException;

abstract class Collection implements \Countable, \IteratorAggregate
{
    public function __construct(private array $items)
    {
        foreach ($items as $item) {
            if (false === is_a($item, $this->type())) {
                throw InvalidArgumentException::createFromMessage(
                    \sprintf('[%s] is not a valid item for collection. Expected [%s]', get_class($item), $this->type())
                );
            }
        }
    }

    public static function init(): static
    {
        return new static([]);
    }

    abstract protected function type(): string;

    public function add($item): void
    {
        if (false === is_a($item, $this->type())) {
            throw InvalidArgumentException::createFromMessage(
                \sprintf('[%s] is not a valid item for collection. Expected [%s]', get_class($item), $this->type())
            );
        }

        $this->items[] = $item;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->getItems());
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->getItems());
    }
}

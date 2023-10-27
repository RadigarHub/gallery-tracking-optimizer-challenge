<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class DoctrineRepository
{
    abstract protected function entityClassName(): string;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    protected function entityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function repository(): EntityRepository
    {
        return $this->entityManager->getRepository($this->entityClassName());
    }
}

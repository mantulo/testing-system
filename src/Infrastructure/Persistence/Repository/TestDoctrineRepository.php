<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Test\Exception\CouldNotGetTest;
use App\Domain\Test\Test;
use App\Domain\Test\TestId;
use App\Domain\Test\TestRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidFactory;

final class TestDoctrineRepository extends ServiceEntityRepository implements TestRepository
{
    public function __construct(
        private readonly UuidFactory $uuidFactory,
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, Test::class);
    }

    public function nextId(): TestId
    {
        return TestId::fromString(
            $this->uuidFactory->uuid7()->toString()
        );
    }

    public function byId(TestId $id): Test
    {
        $test = $this->find($id->asString());

        if (!$test instanceof Test) {
            throw CouldNotGetTest::withId($id->asString());
        }

        return $test;
    }

    public function findLast(): ?Test
    {
        /** @var Test|null $test */
        $test = $this->createQueryBuilder('t')
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();

        return $test;
    }

    public function save(Test $test): void
    {
        $this->getEntityManager()->persist($test);
        $this->getEntityManager()->flush();
    }
}

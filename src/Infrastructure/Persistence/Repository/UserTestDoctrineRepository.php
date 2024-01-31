<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Test\Exception\CouldNotGetTest;
use App\Domain\UserTest\UserTest;
use App\Domain\UserTest\UserTestId;
use App\Domain\UserTest\UserTestRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidFactory;

/**
 * @extends ServiceEntityRepository<UserTest>
 */
final class UserTestDoctrineRepository extends ServiceEntityRepository implements UserTestRepository
{
    public function __construct(
        private readonly UuidFactory $uuidFactory,
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, UserTest::class);
    }

    public function nextId(): UserTestId
    {
        return UserTestId::fromString(
            $this->uuidFactory->uuid7()->toString()
        );
    }

    public function byId(UserTestId $id): UserTest
    {
        $test = $this->find($id->asString());

        if (!$test instanceof UserTest) {
            throw CouldNotGetTest::withId($id->asString());
        }

        return $test;
    }

    public function save(UserTest $test): void
    {
        $this->getEntityManager()->persist($test);
        $this->getEntityManager()->flush();
    }
}

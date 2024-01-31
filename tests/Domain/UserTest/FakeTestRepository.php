<?php

declare(strict_types=1);

namespace App\Tests\Domain\UserTest;

use App\Domain\Test\Exception\CouldNotGetTest;
use App\Domain\Test\Test;
use App\Domain\Test\TestId;
use App\Domain\Test\TestRepository;
use Ramsey\Uuid\UuidFactory;

final readonly class FakeTestRepository implements TestRepository
{
    public function __construct(
        private UuidFactory $uuidFactory,
    ) {
    }

    public function nextId(): TestId
    {
        return TestId::fromString(
            $this->uuidFactory->uuid7()->toString()
        );
    }

    public function byId(TestId $id): Test
    {
        throw CouldNotGetTest::withId($id->asString());
    }

    public function findLast(): ?Test
    {
        return null;
    }

    public function save(Test $test): void
    {
        // simulates saving to generate questions id
        foreach ($test->questions() as $id => $question) {
            $reflection = new \ReflectionClass(get_class($question));
            $property = $reflection->getProperty('id');
            $property->setAccessible(true);
            $property->setValue($question, (int) $id + 1);
        }
    }
}
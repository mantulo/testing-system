<?php

declare(strict_types=1);

namespace App\UseCase\StartTest;

use App\Domain\Test\TestId;
use App\Domain\UserTest\User;

final readonly class StartTest
{
    public function __construct(
        private string $testId,
        private string $firstName,
        private string $lastName,
    ) {
    }

    public function testId(): TestId
    {
        return TestId::fromString($this->testId);
    }

    public function user(): User
    {
        return new User($this->firstName, $this->lastName);
    }
}

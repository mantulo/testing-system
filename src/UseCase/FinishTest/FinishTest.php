<?php

declare(strict_types=1);

namespace App\UseCase\FinishTest;

use App\Domain\UserTest\UserTestId;

final readonly class FinishTest
{
    public function __construct(
        private string $testId,
    ) {
    }

    public function testId(): UserTestId
    {
        return UserTestId::fromString($this->testId);
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase\StartTest;

final readonly class StartTest
{
    public function __construct(
        public string $testId,
        public string $firstName,
        public string $lastName,
    ) {
    }
}
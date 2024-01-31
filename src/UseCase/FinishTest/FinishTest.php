<?php

declare(strict_types=1);

namespace App\UseCase\FinishTest;

final readonly class FinishTest
{
    public function __construct(
        public string $testId,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase\FinishTest;

use App\Domain\UserTest\UserTestId;
use App\Domain\UserTest\UserTestRepository;

final readonly class FinishTestHandler
{
    public function __construct(
        private UserTestRepository $repository,
    ) {
    }

    public function handle(FinishTest $command): void
    {
        $testId = new UserTestId($command->testId);

        $test = $this->repository->byId($testId);
        $test->markAsFinished();

        $this->repository->save($test);
    }
}
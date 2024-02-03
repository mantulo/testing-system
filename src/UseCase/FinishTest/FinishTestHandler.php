<?php

declare(strict_types=1);

namespace App\UseCase\FinishTest;

use App\Domain\UserTest\UserTestRepository;

final readonly class FinishTestHandler
{
    public function __construct(
        private UserTestRepository $repository,
    ) {
    }

    public function handle(FinishTest $request): void
    {
        $test = $this->repository->byId($request->testId());
        $test->markAsFinished();

        $this->repository->save($test);
    }
}

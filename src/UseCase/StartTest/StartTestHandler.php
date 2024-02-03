<?php

declare(strict_types=1);

namespace App\UseCase\StartTest;

use App\Domain\Test\TestRepository;
use App\Domain\UserTest\UserTest;
use App\Domain\UserTest\UserTestRepository;

final readonly class StartTestHandler
{
    public function __construct(
        private UserTestRepository $userTestRepository,
        private TestRepository $testRepository,
    ) {
    }

    public function handle(StartTest $request): UserTest
    {
        $test = $this->testRepository->byId($request->testId());
        $userTestId = $this->userTestRepository->nextId();
        $userTest = new UserTest($userTestId, $request->user(), $test);

        $this->userTestRepository->save($userTest);

        return $userTest;
    }
}

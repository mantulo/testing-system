<?php

declare(strict_types=1);

namespace App\UseCase\StartTest;

use App\Domain\Test\TestId;
use App\Domain\Test\TestRepository;
use App\Domain\UserTest\User;
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
        $testId = TestId::fromString($request->testId);
        $test = $this->testRepository->byId($testId);

        $user = new User($request->firstName, $request->lastName);
        $userTestId = $this->userTestRepository->nextId();
        $userTest = new UserTest($userTestId, $user, $test);

        $this->userTestRepository->save($userTest);

        return $userTest;
    }
}

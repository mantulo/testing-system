<?php

declare(strict_types=1);

namespace App\UseCase\AnswerQuestion;

use App\Domain\UserTest\UserTestRepository;

final readonly class AnswerQuestionHandler
{
    public function __construct(
        private UserTestRepository $userTestRepository,
    ) {
    }

    public function handle(AnswerQuestion $request): void
    {
        $userTest = $this->userTestRepository->byId($request->userTestId());
        $userTest->acceptMultipleAnswersByIds($request->questionId(), $request->answerIds());

        $this->userTestRepository->save($userTest);
    }
}

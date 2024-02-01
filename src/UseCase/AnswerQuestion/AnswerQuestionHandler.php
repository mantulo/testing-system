<?php

declare(strict_types=1);

namespace App\UseCase\AnswerQuestion;

use App\Domain\UserTest\UserTestId;
use App\Domain\UserTest\UserTestRepository;

final readonly class AnswerQuestionHandler
{
    public function __construct(
        private UserTestRepository $userTestRepository,
    ) {
    }

    public function handle(AnswerQuestion $request): void
    {
        $userTestId = UserTestId::fromString($request->testId);
        $userTest = $this->userTestRepository->byId($userTestId);
        $question = $userTest->getQuestionById($request->questionId);

        $answers = [];

        foreach ($request->answerIds as $answerId) {
            $answers[] = $question->getAnswerById($answerId);
        }

        $userTest->acceptMultipleAnswers($question, $answers);
        $this->userTestRepository->save($userTest);
    }
}

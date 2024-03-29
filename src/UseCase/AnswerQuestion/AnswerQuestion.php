<?php

declare(strict_types=1);

namespace App\UseCase\AnswerQuestion;

use App\Domain\Test\AnswerId;
use App\Domain\Test\QuestionId;
use App\Domain\UserTest\UserTestId;
use Webmozart\Assert\Assert;

final readonly class AnswerQuestion
{
    /**
     * @param string     $testId
     * @param int        $questionId
     * @param array<int> $answerIds
    */
    public function __construct(
        private string $testId,
        private int $questionId,
        private array $answerIds,
    ) {
    }

    public function userTestId(): UserTestId
    {
        return UserTestId::fromString($this->testId);
    }

    public function questionId(): QuestionId
    {
        return QuestionId::fromInt($this->questionId);
    }

    /**
     * @return AnswerId[]
     */
    public function answerIds(): array
    {
        Assert::allInteger($this->answerIds);

        return array_map(fn (int $id) => AnswerId::fromInt($id), $this->answerIds);
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase\AnswerQuestion;

final class AnswerQuestion
{
    /**
     * @param string     $testId
     * @param int        $questionId
     * @param array<int> $answerIds
    */
    public function __construct(
        public string $testId,
        public int $questionId,
        public array $answerIds,
    ) {
    }
}

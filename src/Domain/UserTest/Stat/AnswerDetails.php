<?php

declare(strict_types=1);

namespace App\Domain\UserTest\Stat;

use App\Domain\Test\Answer;
use App\Domain\Test\Question;
use App\Domain\UserTest\UserAnswer;

final class AnswerDetails
{
    /**
     * @param Question     $question
     * @param UserAnswer[] $acceptedAnswers
     * @param Answer[]     $expectedAnswers
     */
    public function __construct(
        public Question $question,
        public array $acceptedAnswers,
        public array $expectedAnswers,
    ) {
    }

    public function question(): string
    {
        return $this->question->text();
    }

    public function expectedAnswers(): string
    {
        return implode(', ', array_map(fn (Answer $answer) => $answer->text(), $this->expectedAnswers));
    }

    public function acceptedAnswers(): string
    {
        return implode(', ', array_map(fn (UserAnswer $userAnswer) => $userAnswer->text(), $this->acceptedAnswers));
    }
}

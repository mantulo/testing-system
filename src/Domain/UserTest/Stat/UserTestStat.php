<?php

declare(strict_types=1);

namespace App\Domain\UserTest\Stat;

use App\Domain\Test\Question;
use App\Domain\UserTest\UserAnswer;

final readonly class UserTestStat
{
    /**
     * @param AnswerDetails[] $correctAnswerDetails
     * @param AnswerDetails[] $incorrectAnswerDetails
     */
    public function __construct(
        public array $correctAnswerDetails,
        public array $incorrectAnswerDetails,
    ) {
    }

    /**
     * @param array<array{'question': Question, 'answers': array<UserAnswer>}> $data
     *
     * @return self
     */
    public static function fromData(array $data): self
    {
        $correct = [];
        $incorrect = [];

        foreach ($data as $entry) {
            $question = $entry['question'];

            foreach ($entry['answers'] as $answer) {
                if (!$answer->isCorrect()) {
                    $incorrect[] = new AnswerDetails($question, $entry['answers'], $question->correctAnswers());

                    continue 2;
                }
            }

            $correct[] = new AnswerDetails($question, $entry['answers'], $question->correctAnswers());
        }

        return new self($correct, $incorrect);
    }

    public function correctAnsweredCount(): int
    {
        return count($this->correctAnswerDetails);
    }

    public function incorrectAnsweredCount(): int
    {
        return count($this->incorrectAnswerDetails);
    }

    public function answeredCount(): int
    {
        return count($this->incorrectAnswerDetails) + count($this->correctAnswerDetails);
    }
}

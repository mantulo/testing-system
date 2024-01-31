<?php

declare(strict_types=1);

namespace App\Domain\UserTest\Stat;

use App\Domain\Test\Question;
use App\Domain\UserTest\UserAnswer;

final readonly class UserTestStat
{
    /**
     * @param AnswerDetails[] $correct
     * @param AnswerDetails[] $incorrect
     */
    public function __construct(
        public array $correct,
        public array $incorrect,
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
            foreach ($entry['answers'] as $answer) {
                $question = $entry['question'];

                if (!$answer->isCorrect()) {
                    $incorrect[] = new AnswerDetails($question, $entry['answers'], $question->correctAnswers());
                    continue 2;
                }

                $correct[] = new AnswerDetails($question, $entry['answers'], $question->correctAnswers());
            }
        }

        return new self($correct, $incorrect);
    }
}
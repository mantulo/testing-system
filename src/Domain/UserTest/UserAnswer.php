<?php

declare(strict_types=1);

namespace App\Domain\UserTest;

use App\Domain\Test\Answer;
use App\Domain\Test\Question;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity]
#[UniqueConstraint('test_question_answer', fields: ['test', 'question', 'answer'])]
class UserAnswer
{
    #[Id]
    #[Column]
    #[GeneratedValue]
    private int|null $id = null;

    #[ManyToOne(targetEntity: UserTest::class, cascade: ['persist'], inversedBy: 'answers')]
    private UserTest $test;

    #[ManyToOne(targetEntity: Question::class)]
    private Question $question;

    #[ManyToOne(targetEntity: Answer::class)]
    private Answer $answer;

    public function __construct(
        UserTest $test,
        Question $question,
        Answer $answer
    ) {
        $this->test = $test;
        $this->question = $question;
        $this->answer = $answer;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function question(): Question
    {
        return $this->question;
    }

    public function answer(): Answer
    {
        return $this->answer;
    }

    public function equals(self $other): bool
    {
        return $other->question() === $this->question()
            && $other->answer() === $this->answer();
    }

    public function isCorrect(): bool
    {
        return $this->answer->isCorrect();
    }

    public function test(): UserTest
    {
        return $this->test;
    }

    public function text(): string
    {
        return $this->answer->text();
    }
}
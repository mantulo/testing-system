<?php

declare(strict_types=1);

namespace App\Domain\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Webmozart\Assert\Assert;

#[Entity]
class Question
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    private int|null $id;

    #[Column(type: 'string', length: 255)]
    private string $text;

    /**
     * @var Collection<int, Answer>
     */
    #[OneToMany(mappedBy: 'question', targetEntity: Answer::class, cascade: ['persist'])]
    private Collection $answers;

    #[ManyToOne(targetEntity: Test::class, inversedBy: 'questions')]
    private Test $test;

    /**
     * @param Test     $test
     * @param string   $text
     * @param Answer[] $answers
     */
    public function __construct(
        Test $test,
        string $text,
        array $answers,
    ) {
        Assert::allIsInstanceOf($answers, Answer::class);

        $this->id = null;
        $this->text = $text;
        $this->test = $test;
        $this->answers = new ArrayCollection($answers);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function addAnswer(string $text, bool $correct): Answer
    {
        $answer = new Answer($this, $text, $correct);
        $this->answers->add($answer);

        return $answer;
    }

    /**
     * @return Answer[]
     */
    public function correctAnswers(): array
    {
        return array_filter(
            $this->answers->toArray(),
            fn(Answer $answer) => $answer->isCorrect()
        );
    }

    /**
     * @return Answer[]
     */
    public function shuffledAnswers(): array
    {
        $questions = $this->answers->toArray();
        shuffle($questions);

        return $questions;
    }

    public function answerWithText(string $text): Answer
    {
        /** @var Answer $answer */
        foreach ($this->answers as $answer) {
            if ($answer->text() === $text) {
                return $answer;
            }
        }

        throw new \RuntimeException(
            sprintf('There is no answer "%s" in test "%s".', $text, $this->test->id()->asString())
        );
    }

    public function test(): Test
    {
        return $this->test;
    }
}
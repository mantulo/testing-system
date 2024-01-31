<?php

declare(strict_types=1);

namespace App\Domain\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Webmozart\Assert\Assert;

#[Entity]
class Test
{
    #[Id]
    #[Column(type: 'uuid')]
    private string $id;

    /**
     * @var Collection<int, Question>
     */
    #[OneToMany(mappedBy: 'test', targetEntity: Question::class, cascade: ['persist'])]
    private Collection $questions;

    #[Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    /**
     * @param TestId     $testId
     * @param Question[] $questions
     */
    public function __construct(
        TestId $testId,
        array $questions,
    ) {
        Assert::allIsInstanceOf($questions, Question::class);

        $this->id = $testId->asString();
        $this->questions = new ArrayCollection($questions);
        $this->createdAt = new \DateTimeImmutable();
    }

    public function id(): TestId
    {
        return TestId::fromString($this->id);
    }

    /**
     * @return Question[]
     */
    public function questions(): array
    {
        return $this->questions->toArray();
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function addQuestion(string $text): Question
    {
        $question = new Question($this, $text, []);
        $this->questions->add($question);

        return $question;
    }
}
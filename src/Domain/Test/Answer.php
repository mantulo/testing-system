<?php

declare(strict_types=1);

namespace App\Domain\Test;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class Answer
{
    #[Id]
    #[Column]
    #[GeneratedValue]
    private int $id;

    #[ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
    private Question $question;

    #[Column(type: 'string', length: 255)]
    private string $text;

    #[Column(type: 'boolean')]
    private bool $correct;

    public function __construct(
        Question $question,
        string $text,
        bool $correct,
    ) {
        $this->question = $question;
        $this->text = $text;
        $this->correct = $correct;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function questionId(): int
    {
        return $this->question->id();
    }

    public function question(): Question
    {
        return $this->question;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function isCorrect(): bool
    {
        return $this->correct;
    }
}
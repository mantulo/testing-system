<?php

declare(strict_types=1);

namespace App\Domain\UserTest;

use App\Domain\Test\Answer;
use App\Domain\Test\AnswerId;
use App\Domain\Test\Question;
use App\Domain\Test\QuestionId;
use App\Domain\Test\Test;
use App\Domain\UserTest\Exception\CouldNotFinishTest;
use App\Domain\UserTest\Stat\UserTestStat;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Webmozart\Assert\Assert;

#[Entity]
class UserTest
{
    #[Id]
    #[Column(type: 'uuid')]
    private string $id;

    #[Embedded]
    private User $user;

    #[ManyToOne(targetEntity: Test::class)]
    private Test $test;

    /**
     * @var Collection<int, UserAnswer>
     */
    #[OneToMany(mappedBy: 'test', targetEntity: UserAnswer::class, cascade: ['persist'])]
    private Collection $answers;

    #[Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[Column(type: 'boolean')]
    private bool $finished = false;

    #[Column(type: 'datetime_immutable', nullable: true)]
    private \DateTimeImmutable|null $finishedAt = null;

    public function __construct(
        UserTestId $id,
        User $user,
        Test $test,
    ) {
        $this->id = $id->asString();
        $this->user = $user;
        $this->test = $test;
        $this->answers = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function id(): UserTestId
    {
        return UserTestId::fromString($this->id);
    }

    /**
     * @return Question[]
     */
    public function shuffledQuestions(): array
    {
        $questions = $this->test->questions();
        shuffle($questions);

        return $questions;
    }

    public function acceptAnswer(Question $question, Answer $answer): void
    {
        $newUserAnswer = new UserAnswer($this, $question, $answer);

        /** @var UserAnswer $userAnswer */
        foreach ($this->answers as $userAnswer) {
            if ($userAnswer->equals($newUserAnswer)) {
                return;
            }
        }

        $this->answers->add($newUserAnswer);
    }

    /**
     * @param QuestionId $questionId
     * @param AnswerId[] $answerIds
     *
     * @return void
     */
    public function acceptMultipleAnswersByIds(QuestionId $questionId, array $answerIds): void
    {
        Assert::allIsInstanceOf($answerIds, AnswerId::class);
        Assert::minCount($answerIds, 1);

        $question = $this->getQuestionById($questionId);

        foreach ($answerIds as $answerId) {
            $answer = $question->getAnswerById($answerId);
            $this->acceptAnswer($question, $answer);
        }
    }

    public function markAsFinished(): void
    {
        if ($this->finished) {
            throw CouldNotFinishTest::causeTestAlreadyFinished();
        }

        if (count($this->answersGroupedByQuestion()) !== $this->test->questionsCount()) {
            throw CouldNotFinishTest::withIncompleteAnswers($this->id);
        }

        $this->finished = true;
        $this->finishedAt = new \DateTimeImmutable();
    }

    public function stats(): UserTestStat
    {
        return UserTestStat::fromData($this->answersGroupedByQuestion());
    }

    /**
     * @return array<int, array{'question': Question, 'answers': array<int, UserAnswer>}>
     */
    public function answersGroupedByQuestion(): array
    {
        $groupedQuestions = [];

        /** @var UserAnswer $answer */
        foreach ($this->answers as $answer) {
            $questionHash = spl_object_hash($answer->question());
            $groupedQuestions[$questionHash]['question'] = $answer->question();
            $groupedQuestions[$questionHash]['answers'][] = $answer;
        }

        return array_values($groupedQuestions);
    }

    public function user(): User
    {
        return $this->user;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function finishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    private function getQuestionById(QuestionId $questionId): Question
    {
        return $this->test->getQuestionById($questionId);
    }
}

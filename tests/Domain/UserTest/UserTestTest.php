<?php

declare(strict_types=1);

namespace App\Tests\Domain\UserTest;

use App\Domain\Test\Question;
use App\Domain\Test\Test;
use App\Domain\Test\TestId;
use App\Domain\UserTest\Exception\CouldNotFinishTest;
use App\Domain\UserTest\User;
use App\Domain\UserTest\UserTest;
use App\Domain\UserTest\UserTestId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidFactory;

use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

final class UserTestTest extends TestCase
{
    public function testItAcceptSingleCorrectAnswer(): void
    {
        $testId = TestId::fromString($this->nextUuid());

        $test = new Test($testId, []);
        $question1 = $test->addQuestion('1 + 1');

        // required to group answers by question id
        $this->bindIncrementalIdToQuestionHook($question1);

        $correctAnswer1 = $question1->addAnswer('2', correct: true);
        $correctAnswer2 = $question1->addAnswer('2 + 0', correct: true);
        $invalidAnswer1 = $question1->addAnswer('3', correct: false);
        $invalidAnswer2 = $question1->addAnswer('3', correct: false);

        $userTest = new UserTest(
            UserTestId::fromString($this->nextUuid()),
            $this->user(),
            $test
        );

        $userTest->acceptAnswer($question1, $correctAnswer1);
        $stat = $userTest->stats();

        assertThat($stat->answeredCount(), equalTo(1));
        assertThat($stat->correctAnsweredCount(), equalTo(1));
        assertThat($stat->incorrectAnsweredCount(), equalTo(0));
    }

    public function testItAcceptMultipleCorrectAnswer(): void
    {
        $testId = TestId::fromString($this->nextUuid());

        $test = new Test($testId, []);
        $question1 = $test->addQuestion('2 + 2');

        // required to group answers by question id
        $this->bindIncrementalIdToQuestionHook($question1);

        $invalidAnswer1 = $question1->addAnswer('2', correct: false);
        $invalidAnswer2 = $question1->addAnswer('3', correct: false);
        $correctAnswer1 = $question1->addAnswer('4', correct: true);
        $correctAnswer2 = $question1->addAnswer('4 + 0', correct: true);

        $userTest = new UserTest(
            UserTestId::fromString($this->nextUuid()),
            $this->user(),
            $test
        );

        $userTest->acceptAnswer($question1, $correctAnswer1);
        $userTest->acceptAnswer($question1, $correctAnswer2);

        $stat = $userTest->stats();

        assertThat($stat->correctAnsweredCount(), equalTo(1));
        assertThat($stat->incorrectAnsweredCount(), equalTo(0));
        assertThat($stat->answeredCount(), equalTo($test->questionsCount()));
    }

    public function testItShouldAnswerWithCorrectAndIncorrectOption(): void
    {
        $testId = TestId::fromString($this->nextUuid());

        $test = new Test($testId, []);
        $question1 = $test->addQuestion('2 + 2');

        // required to group answers by question id
        $this->bindIncrementalIdToQuestionHook($question1);

        $invalidAnswer1 = $question1->addAnswer('2', correct: false);
        $invalidAnswer2 = $question1->addAnswer('3', correct: false);
        $correctAnswer1 = $question1->addAnswer('4', correct: true);
        $correctAnswer2 = $question1->addAnswer('4 + 0', correct: true);

        $userTest = new UserTest(
            UserTestId::fromString($this->nextUuid()),
            $this->user(),
            $test
        );

        $userTest->acceptAnswer($question1, $invalidAnswer1);
        $userTest->acceptAnswer($question1, $correctAnswer1);

        $stat = $userTest->stats();

        assertThat($stat->correctAnsweredCount(), equalTo(0));
        assertThat($stat->incorrectAnsweredCount(), equalTo(1));
        assertThat($stat->answeredCount(), equalTo($test->questionsCount()));
        assertThat($stat->answeredCount(), equalTo(1));
    }

    public function testItShouldThrowsAnExceptionIfUserTriesFinishIncompleteTest(): void
    {
        $testId = TestId::fromString($this->nextUuid());
        $test = new Test($testId, []);

        $question1 = $test->addQuestion('1 + 1');
        $question1->addAnswer('2', correct: true);
        $question1->addAnswer('3', correct: false);
        $question1->addAnswer('3', correct: false);

        $this->bindIncrementalIdToQuestionHook($question1);

        $userTest = new UserTest(
            UserTestId::fromString($this->nextUuid()),
            $this->user(),
            $test
        );

        $stats = $userTest->stats();

        assertThat($stats->answeredCount(), equalTo(0));
        assertThat($test->questionsCount(), equalTo(1));

        $this->expectException(CouldNotFinishTest::class);

        $userTest->markAsFinished();
    }

    private function nextUuid(): string
    {
        return (new UuidFactory())->uuid7()->toString();
    }

    public function user(): User
    {
        return new User('John', 'Doe');
    }

    public function bindIncrementalIdToQuestionHook(Question ...$questions): void
    {
        foreach ($questions as $id => $question) {
            $reflection = new \ReflectionClass(get_class($question));
            $property = $reflection->getProperty('id');
            $property->setAccessible(true);
            $property->setValue($question, (int) $id + 1);
        }
    }
}

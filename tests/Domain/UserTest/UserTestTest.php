<?php

declare(strict_types=1);

namespace App\Tests\Domain\UserTest;

use App\Domain\Test\Test;
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
        $repository = $this->fakeTestRepository();
        $testId = $repository->nextId();

        $test = new Test($testId, []);
        $question1 = $test->addQuestion('1 + 1');

        // simulates generation of question ids
        $repository->save($test);

        $correctAnswer1 = $question1->addAnswer('2', correct: true);
        $correctAnswer2 = $question1->addAnswer('2 + 0', correct: true);
        $invalidAnswer1 = $question1->addAnswer('3', correct: false);
        $invalidAnswer2 = $question1->addAnswer('3', correct: false);

        $userTest = new UserTest(
            $this->nextUserTestId(),
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
        $repository = $this->fakeTestRepository();
        $testId = $repository->nextId();

        $test = new Test($testId, []);
        $question1 = $test->addQuestion('2 + 2');

        // simulates generation of question ids
        $repository->save($test);

        $invalidAnswer1 = $question1->addAnswer('2', correct: false);
        $invalidAnswer2 = $question1->addAnswer('3', correct: false);
        $correctAnswer1 = $question1->addAnswer('4', correct: true);
        $correctAnswer2 = $question1->addAnswer('4 + 0', correct: true);

        $userTest = new UserTest(
            $this->nextUserTestId(),
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
        $repository = $this->fakeTestRepository();
        $testId = $repository->nextId();

        $test = new Test($testId, []);
        $question1 = $test->addQuestion('2 + 2');

        // simulates generation of question ids
        $repository->save($test);

        $invalidAnswer1 = $question1->addAnswer('2', correct: false);
        $invalidAnswer2 = $question1->addAnswer('3', correct: false);
        $correctAnswer1 = $question1->addAnswer('4', correct: true);
        $correctAnswer2 = $question1->addAnswer('4 + 0', correct: true);

        $userTest = new UserTest(
            $this->nextUserTestId(),
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
        $repository = $this->fakeTestRepository();

        $testId = $repository->nextId();
        $test = new Test($testId, []);

        $question1 = $test->addQuestion('1 + 1');
        $question1->addAnswer('2', correct: true);
        $question1->addAnswer('3', correct: false);
        $question1->addAnswer('3', correct: false);

        $repository->save($test);

        $userTest = new UserTest(
            $this->nextUserTestId(),
            $this->user(),
            $test
        );

        $stats = $userTest->stats();

        assertThat($stats->answeredCount(), equalTo(0));
        assertThat($test->questionsCount(), equalTo(1));

        $this->expectException(CouldNotFinishTest::class);

        $userTest->markAsFinished();
    }

    private function fakeTestRepository(): FakeTestRepository
    {
        return new FakeTestRepository(
            new UuidFactory()
        );
    }

    private function user(): User
    {
        return new User('John', 'Doe');
    }

    private function nextUserTestId(): UserTestId
    {
        return UserTestId::fromString(
            (new UuidFactory())->uuid7()->toString()
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\DataFixtures;

use App\Domain\Test\Test;
use App\Domain\Test\TestRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TestFixtures extends Fixture
{
    public function __construct(
        private readonly TestRepository $testRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $id = $this->testRepository->nextId();

        $questions = [
            [
                'text' => '1 + 1 =',
                'correct_answers' => ['2'],
                'incorrect_answers' => ['3', '0'],
            ], [
                'text' => '2 + 2 =',
                'correct_answers' => ['4', '3 + 1'],
                'incorrect_answers' => ['10'],
            ], [
                'text' => '3 + 3 =',
                'correct_answers' => ['1 + 5', '6', '2 + 4'],
                'incorrect_answers' => ['1'],
            ], [
                'text' => '4 + 4 =',
                'correct_answers' => ['8', '0 + 8'],
                'incorrect_answers' => ['4', '0'],
            ], [
                'text' => '5 + 5 =',
                'correct_answers' => ['10'],
                'incorrect_answers' => ['6', '18', '9', '0'],
            ], [
                'text' => '6 + 6 =',
                'correct_answers' => ['12', '5 + 7'],
                'incorrect_answers' => ['3', '9', '0'],
            ], [
                'text' => '7 + 7 =',
                'correct_answers' => ['14'],
                'incorrect_answers' => ['5'],
            ], [
                'text' => '8 + 8 =',
                'correct_answers' => ['16'],
                'incorrect_answers' => ['12', '9', '5'],
            ], [
                'text' => '9 + 9 =',
                'correct_answers' => ['18', '17 + 1', '2 + 16'],
                'incorrect_answers' => ['9'],
            ], [
                'text' => '10 + 10 =',
                'correct_answers' => ['20'],
                'incorrect_answers' => ['0', '2', '8'],
            ],
        ];

        $test = new Test($id, []);

        foreach ($questions as $item) {
            $question = $test->addQuestion($item['text']);

            foreach ($item['correct_answers'] as $answer) {
                $question->addAnswer($answer, correct: true);
            }

            foreach ($item['incorrect_answers'] as $answer) {
                $question->addAnswer($answer, correct: false);
            }
        }

        $manager->persist($test);
        $manager->flush();
    }
}

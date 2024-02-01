<?php

declare(strict_types=1);

namespace App\Infrastructure\Ui\Command;

use App\Domain\UserTest\UserTestRepository;
use App\UseCase\QueryLastTest\QueryLastTest;
use App\UseCase\QueryLastTest\QueryLastTestHandler;
use App\UseCase\StartTest\StartTest;
use App\UseCase\StartTest\StartTestHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test:run',
    description: 'Start last available logic test.',
)]
final class RunTestCommand extends Command
{
    use LockableTrait;

    public function __construct(
        private readonly QueryLastTestHandler $queryHandler,
        private readonly StartTestHandler $startTestHandler,
        private readonly UserTestRepository $repository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock()) {
            $output->writeln('An instance of this command is already running.');

            return 0;
        }

        $test = $this->queryHandler->query(new QueryLastTest());

        if ($test === null) {
            $output->writeln('There is no tests in database.');

            return self::FAILURE;
        }

        $io = new SymfonyStyle($input, $output);

        /** @var string $firstName */
        $firstName = $io->ask('Enter first name, please');
        /** @var string $lastName */
        $lastName = $io->ask('Enter last name, please');

        $startTest = new StartTest($test->id()->asString(), $firstName, $lastName);

        $startedTest = $this->startTestHandler->handle($startTest);
        $shuffledQuestion = $startedTest->shuffledQuestions();

        foreach ($shuffledQuestion as $question) {
            /** @var string[] $acceptedAnswers */
            $acceptedAnswers = $io->choice($question->text(), $question->shuffledAnswersList(), multiSelect: true);

            foreach ($acceptedAnswers as $acceptedAnswer) {
                $answer = $question->getAnswerByText($acceptedAnswer);
                $startedTest->acceptAnswer($question, $answer);
            }
        }

        $this->repository->save($startedTest);

        $stats = $startedTest->stats();

        $table = new Table($output);
        $table->setHeaders(['Question', 'Accepted incorrect answer', 'Expected answers']);

        foreach ($stats->incorrectAnswerDetails as $answerDetails) {
            $table->addRow([
                $answerDetails->question(),
                $answerDetails->acceptedAnswers(),
                $answerDetails->expectedAnswers()
            ]);
        }

        $table->render();

        $table = new Table($output);

        $table->setHeaders(['Question', 'Accepted correct answer', 'Expected answers']);

        foreach ($stats->correctAnswerDetails as $answerDetails) {
            $table->addRow([
                $answerDetails->question(),
                $answerDetails->acceptedAnswers(),
                $answerDetails->expectedAnswers()
            ]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}

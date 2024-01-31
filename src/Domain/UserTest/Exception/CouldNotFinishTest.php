<?php

declare(strict_types=1);

namespace App\Domain\UserTest\Exception;

final class CouldNotFinishTest extends \RuntimeException
{
    public static function causeTestAlreadyFinished(): self
    {
        return new self('Could not finish already have finished test.');
    }

    public static function withIncompleteAnswers(string $testId): self
    {
        return new self(
            sprintf('Could not finish incomplete test with id "%s".', $testId)
        );
    }
}

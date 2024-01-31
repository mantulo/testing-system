<?php

declare(strict_types=1);

namespace App\Domain\Test\Exception;

final class CouldNotGetTest extends \RuntimeException
{
    public static function withId(string $id): self
    {
        return new self(
            sprintf('Could not get test with id "%s"', $id)
        );
    }
}
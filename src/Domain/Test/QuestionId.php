<?php

declare(strict_types=1);

namespace App\Domain\Test;

use Webmozart\Assert\Assert;

final readonly class QuestionId
{
    public function __construct(private int $value)
    {
        Assert::notEmpty($value, 'Answer ID cannot be empty.');
        Assert::greaterThan($this->value, 0);
    }

    public function asString(): string
    {
        return (string) $this->value;
    }

    public static function fromString(string $value): self
    {
        return new self((int) $value);
    }

    public static function fromInt(int $value): self
    {
        return new self($value);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}

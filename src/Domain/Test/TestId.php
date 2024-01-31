<?php

declare(strict_types=1);

namespace App\Domain\Test;

use Webmozart\Assert\Assert;

final readonly class TestId
{
    public function __construct(private string $value)
    {
        Assert::notEmpty($value, 'Id cannot be empty.');
        Assert::uuid($this->value);
    }

    public function asString(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }
}
<?php

declare(strict_types=1);

namespace App\Domain\UserTest;

use Webmozart\Assert\Assert;

final class UserTestId
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value, 'Id cannot be empty');
        Assert::uuid($value);

        $this->value = $value;
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

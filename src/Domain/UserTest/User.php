<?php

declare(strict_types=1);

namespace App\Domain\UserTest;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Webmozart\Assert\Assert;

#[Embeddable]
class User
{
    public function __construct(
        #[Column(type: 'string', length: 25)]
        private string $firstName,
        #[Column(type: 'string', length: 25)]
        private string $lastName,
    ) {
        Assert::notEmpty($this->firstName, 'First name cannot be empty.');
        Assert::notEmpty($this->lastName, 'Last name cannot be empty.');

        if (mb_strlen($this->firstName) > 25) {
            throw new \InvalidArgumentException('First name cannot be greater than 25 chars.');
        }

        if (mb_strlen($this->lastName) > 25) {
            throw new \InvalidArgumentException('Last name cannot be greater than 25 chars.');
        }
    }

    public function name(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }
}
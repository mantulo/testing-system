<?php

declare(strict_types=1);

namespace App\Domain\UserTest;

interface UserTestRepository
{
    public function nextId(): UserTestId;

    public function byId(UserTestId $id): UserTest;

    public function save(UserTest $userTest): void;
}
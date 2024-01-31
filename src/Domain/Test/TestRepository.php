<?php

declare(strict_types=1);

namespace App\Domain\Test;

interface TestRepository
{
    public function nextId(): TestId;

    public function byId(TestId $id): Test;

    public function findLast(): ?Test;

    public function save(Test $test): void;
}
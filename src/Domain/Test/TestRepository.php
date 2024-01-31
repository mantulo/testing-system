<?php

declare(strict_types=1);

namespace App\Domain\Test;

use App\Domain\Test\Exception\CouldNotGetTest;

interface TestRepository
{
    public function nextId(): TestId;

    /**
     * @param TestId $id
     *
     * @return Test
     *
     * @throws CouldNotGetTest
     */
    public function byId(TestId $id): Test;

    public function findLast(): ?Test;

    public function save(Test $test): void;
}

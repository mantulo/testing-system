<?php

declare(strict_types=1);

namespace App\UseCase\QueryLastTest;

use App\Domain\Test\Test;
use App\Domain\Test\TestRepository;

final readonly class QueryLastTestHandler
{
    public function __construct(
        private TestRepository $repository,
    ) {
    }

    public function query(QueryLastTest $query): ?Test
    {
        return $this->repository->findLast();
    }
}
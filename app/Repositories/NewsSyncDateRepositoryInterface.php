<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface NewsSyncDateRepositoryInterface extends EloquentRepositoryInterface
{
    public function findByCreatedAt($date): ?Collection;
}

<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;

interface NewsRepositoryInterface extends EloquentRepositoryInterface
{
    public function paginate($pageSize): ?LengthAwarePaginator;
}

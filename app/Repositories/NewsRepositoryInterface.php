<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;

interface NewsRepositoryInterface extends EloquentRepositoryInterface
{
    public function paginationWithWhere(array $wheres = [], $pageSize = 20): LengthAwarePaginator;
}

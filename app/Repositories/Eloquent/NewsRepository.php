<?php

namespace App\Repositories\Eloquent;

use App\Models\News;
use App\Repositories\NewsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsRepository extends BaseRepository implements NewsRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(News $model)
    {
        $this->model = $model;
    }

    public function paginationWithWhere(array $wheres = [], $pageSize = 20): LengthAwarePaginator
    {
        $result = $this->model;
        foreach ($wheres as $where) {
            if (count($where) === 3) {
                $result = $result->where($where[0], $where[1], $where[2]);
            } else if (count($where) === 2) {
                $result = $result->where($where[0], $where[1]);
            }
        }
        return $result->paginate($pageSize);
    }
}

<?php

namespace App\Repositories\Eloquent;

use App\Models\News;
use App\Repositories\NewsRepositoryInterface;
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

    public function paginate($pageSize): ?LengthAwarePaginator
    {
        return $this->model->paginate($pageSize);
    }

}

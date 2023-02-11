<?php

namespace App\Repositories\Eloquent;

use App\Models\NewsCategory;
use App\Repositories\NewsCategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class NewsCategoryRepository extends BaseRepository implements NewsCategoryRepositoryInterface
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
    public function __construct(NewsCategory $model)
    {
        $this->model = $model;
    }

}

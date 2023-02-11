<?php

namespace App\Repositories\Eloquent;

use App\Models\NewsContributor;
use App\Repositories\NewsContributorRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class NewsContributorRepository extends BaseRepository implements NewsContributorRepositoryInterface
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
    public function __construct(NewsContributor $model)
    {
        $this->model = $model;
    }
}

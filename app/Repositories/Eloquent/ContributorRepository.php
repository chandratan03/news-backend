<?php

namespace App\Repositories\Eloquent;

use App\Models\Contributor;
use App\Repositories\ContributorRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ContributorRepository extends BaseRepository implements ContributorRepositoryInterface
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
    public function __construct(Contributor $model)
    {
        $this->model = $model;
    }
}

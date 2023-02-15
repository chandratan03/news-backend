<?php

namespace App\Repositories\Eloquent;

use App\Models\Source;
use App\Repositories\SourceRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class SourceRepository extends BaseRepository implements SourceRepositoryInterface
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
    public function __construct(Source $model)
    {
        $this->model = $model;
    }

}

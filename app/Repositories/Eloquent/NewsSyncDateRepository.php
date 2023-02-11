<?php

namespace App\Repositories\Eloquent;

use App\Models\NewsSyncDate;
use App\Repositories\NewsSyncDateRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class NewsSyncDateRepository extends BaseRepository implements NewsSyncDateRepositoryInterface
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
    public function __construct(NewsSyncDate $model)
    {
        $this->model = $model;
    }

    public function findByCreatedAt($date): ?Collection
    {
        return $this->model->whereDate("created_at", $date)->get();
    }
}

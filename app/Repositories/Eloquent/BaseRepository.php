<?php

namespace App\Repositories\Eloquent;

use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
{

    protected $model;
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    public function findById(
        int $modelId,
        array $columns = ['*'],
    ): ?Model {
        return $this->model->select($columns)->findOrFail($modelId);
    }

    public function create(array $payload): ?Model
    {
        $model = $this->model->create($payload);

        return $model->fresh();
    }

    public function update(int $modelId, array $payload): bool
    {
        $model = $this->findById($modelId);

        return $model->update($payload);
    }

    public function deleteById(int $modelId): bool
    {
        return $this->findById($modelId)->delete();
    }

    public function findByWhere(array $wheres = []): Collection
    {
        $result = $this->model;
        foreach ($wheres as $where) {
            if (count($where) === 3) {
                $result = $result->where($where[0], $where[1], $where[2]);
            } else if (count($where) === 2) {
                $result = $result->where($where[0], $where[1]);
            }
        }
        return $result->get();
    }
}

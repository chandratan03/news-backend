<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
    /**
     * Get all models.
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;


    /**
     * Find model by id.
     *
     * @param int $modelId
     * @param array $columns
     * @return Model
     */
    public function findById(
        int $modelId,
        array $columns = ['*'],
    ): ?Model;


    /**
     * Create a model.
     *
     * @param array $payload
     * @return Model
     */
    public function create(array $payload): ?Model;

    /**
     * Update existing model.
     *
     * @param int $modelId
     * @param array $payload
     * @return bool
     */
    public function update(int $modelId, array $payload): bool;

    /**
     * Delete model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function deleteById(int $modelId): bool;

    /**
     * Get all by conditions
     *
     * @param array $wheres
     * @return Collection
     */
    public function findByWhere(array $wheres = []): Collection;
}

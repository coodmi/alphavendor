<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Base repository interface defining common CRUD operations.
 *
 * All repository interfaces should extend this interface to ensure
 * consistent data access patterns across the application.
 */
interface RepositoryInterface
{
    /**
     * Find a model by its primary key.
     *
     * @param  int  $id  The model ID
     * @param  array<string>  $relations  Relations to eager load
     */
    public function find(int $id, array $relations = []): ?Model;

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param  int  $id  The model ID
     * @param  array<string>  $relations  Relations to eager load
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id, array $relations = []): Model;

    /**
     * Get all models.
     *
     * @param  array<string>  $relations  Relations to eager load
     * @return Collection<int, Model>
     */
    public function all(array $relations = []): Collection;

    /**
     * Get models with filters and pagination.
     *
     * @param  array<string, mixed>  $filters  Filter criteria
     * @param  int  $perPage  Items per page
     * @param  array<string>  $relations  Relations to eager load
     */
    public function paginate(array $filters = [], int $perPage = 15, array $relations = []): LengthAwarePaginator;

    /**
     * Create a new model.
     *
     * @param  array<string, mixed>  $data  Model attributes
     */
    public function create(array $data): Model;

    /**
     * Update an existing model.
     *
     * @param  Model  $model  The model to update
     * @param  array<string, mixed>  $data  Updated attributes
     */
    public function update(Model $model, array $data): Model;

    /**
     * Delete a model.
     *
     * @param  Model  $model  The model to delete
     */
    public function delete(Model $model): bool;
}

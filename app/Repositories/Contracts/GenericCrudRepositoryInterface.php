<?php

namespace App\Repositories\Contracts;

interface GenericCrudRepositoryInterface
{
    /**
     * Get all records.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll(): \Illuminate\Support\Collection;

    /**
     * Get a record by ID, optionally loading relationships.
     *
     * @param  int   $id
     * @param  array $with
     * @return mixed
     */
    public function getById(int $id, array $with = []): mixed;

    /**
     * Add a new entity.
     *
     * @param  array $attributes
     * @return mixed
     */
    public function create(array $attributes): mixed;

    /**
     * Update an existing entity.
     *
     * @param  int   $id
     * @param  array $attributes
     * @return bool
     */
    public function update(int $id, array $attributes): bool;

    /**
     * Delete an entity by ID.
     *
     * @param  int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Save pending changes — optional in Eloquent but added for parity.
     *
     * @return bool
     */
    public function saveChanges(): bool;

    /**
     * Execute a custom query with a callback.
     *
     * @param  callable  $query
     * @param  bool      $asNoTracking
     * @return \Illuminate\Support\Collection
     */
    public function executeQuery(callable $query, bool $asNoTracking = false): \Illuminate\Support\Collection;
}

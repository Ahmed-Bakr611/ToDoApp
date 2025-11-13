<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\GenericCrudRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EloquentGenericCrudRepository implements GenericCrudRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getById(int $id, array $with = []): mixed
    {
        return $this->model->with($with)->find($id);
    }

    public function create(array $attributes): mixed
    {
        return $this->model->create($attributes);
    }

    public function update(int $id, array $attributes): bool
    {
        $record = $this->model->find($id);
        return $record ? $record->update($attributes) : false;
    }

    public function delete(int $id): bool
    {
        $record = $this->model->find($id);
        return $record ? (bool) $record->delete() : false;
    }

    public function saveChanges(): bool
    {
        // Eloquent auto-saves by default, so just return true for parity
        return true;
    }

    public function executeQuery(callable $query, bool $asNoTracking = false): Collection
    {
        $builder = $this->model->newQuery();

        if ($asNoTracking) {
            $builder->withoutGlobalScopes();
        }

        return $query($builder)->get();
    }
}

<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;

class Repository implements InterfaceRepository
{
    protected $model;

    /**
     * List all data.
     */
    public function all(array $payload): object
    {
        $search = $payload['search'];
        $cacheKey = $payload['cacheKey'];
        $paginate = $payload['paginate'];
        $minutes = $payload['minutes'];

        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($search, $paginate) {
            $query = $this->model::query();

            if (!empty($search)) {
                $query->filter($search); 
            }

            return $paginate
                ? $query->paginate($paginate)
                : (object) $query->get();
        });
    }

    /**
     * create data.
     */
    public function store(array $data): object
    {
        return $this->model->create($data);
    }

    /**
     * update data.
     */
    public function update(string $id, array $data): array
    {
        return $this->model->findOrFail($id)->update($data);
    }

    /**
     * get by id.
     */
    public function show(string $id): object
    {
        return $this->model->findOrFail($id);
    }

    /**
     * delete by id.
     */
    public function delete(string $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    /**
     * delete by array id.
     */
    public function destroy(array $id): string
    {
        return $this->model->destroy();
    }
}

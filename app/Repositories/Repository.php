<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class Repository implements InterfaceRepository
{
    protected $model;

     /**
     * List where datas.
     */
    public function getIn(array $ids): object
    {
        return $this->model::whereIn('id', $ids)->get();
    }

    /**
     * List all data.
     */
    public function all(array $payload = []): object
    {
        $search   = Arr::get($payload, 'search');
        $cacheKey = Arr::get($payload, 'cacheKey', 'default_key');
        $paginate = Arr::get($payload, 'paginate');
        $minutes  = Arr::get($payload, 'minutes', 5);

        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($search, $paginate) {
            $query = $this->model::query();

            if (!empty($search) && method_exists($this->model, 'scopeFilter')) {
                $query->filter($search);
            }

            if ($paginate) {
                return $query->paginate($paginate);
            }

            return $query->get();
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
    public function update(string $id, array $data): bool
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

    public function updateOrCreate(array $where, array $data): object
    {
        return $this->model::updateOrCreate($where, $data);
    }
}

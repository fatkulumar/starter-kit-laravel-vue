<?php

namespace App\Repositories;

class Repository implements InterfaceRepository
{
    protected $model;
    
    /**
     * List all data pagninate.
     */
    public function paginate(int $paginate): object
    {
        return $this->model->paginate($paginate);
    }

    /**
     * List all data.
     */
    public function all(): object
    {
        return $this->model->all();
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

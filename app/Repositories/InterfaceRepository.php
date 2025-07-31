<?php

namespace App\Repositories;

interface InterfaceRepository
{
    public function paginate(int $paginate): object;
    public function all(): object;
    public function store(array $data): object;
    public function show(string $id): object;
    public function update(string $id, array $data): array;
    public function delete(string $id);
}

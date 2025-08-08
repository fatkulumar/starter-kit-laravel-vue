<?php

namespace App\Repositories;

use phpDocumentor\Reflection\Types\Boolean;

interface InterfaceRepository
{
    public function all(array $payload): object;
    public function store(array $data): object;
    public function show(string $id): object;
    public function update(string $id, array $data): bool;
    public function delete(string $id);
}

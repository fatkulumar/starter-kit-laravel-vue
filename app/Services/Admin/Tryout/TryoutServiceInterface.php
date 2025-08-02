<?php

namespace App\Services\Admin\Tryout;

use App\DataTransferObjects\TryoutDTO;

interface TryoutServiceInterface
{
    public function getTryouts(array $paginate): object;
    public function store(TryoutDTO $tryoutDTO): object;
    public function update(TryoutDTO $tryoutDTO): object;
    public function delete(string $id): bool;
    public function destroy(array $id): array;
}

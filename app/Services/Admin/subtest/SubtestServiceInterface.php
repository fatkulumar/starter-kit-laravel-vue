<?php

namespace App\Services\Admin\subtest;

use App\DataTransferObjects\EventDTO;
use App\DataTransferObjects\SubtestDTO;

interface SubtestServiceInterface
{
    public function getSubtestByTryoutId(array $payload): object;
    public function store(SubtestDTO $subtestDTO): object;
    public function update(SubtestDTO $subtestDTO): object;
    public function delete(string $id): bool;
    public function destroy(array $id): array;
    public function show(string $id): object;
}

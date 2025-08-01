<?php

namespace App\Services\Admin\Event;

use App\DataTransferObjects\UserDTO;

interface UserServiceInterface
{
    public function getEvents(array $paginate): object;
    public function store(UserDTO $userDTO): object;
    public function update(UserDTO $userDTO): object;
    public function delete(string $id): bool;
    public function destroy(array $id): array;
}

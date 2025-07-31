<?php

namespace App\Repositories\Admin\User;

interface UserRepositoryInterface
{
    public function getUsers(array $payload): object;
    public function getUserWithProfileLatest(string $id): object;
    public function getUserWithProfile(string $id): object;
}

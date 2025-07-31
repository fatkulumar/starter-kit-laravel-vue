<?php

namespace App\Repositories\Admin\User;

use App\Models\User;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Cache;

class UserRepository extends Repository implements UserRepositoryInterface
{
    protected $model;

     /**
     * Create a new class instance.
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * List all data pagninate.
     */
    public function getUsers(array $payload): object
    {
        $search = $payload['search'];
        $cacheKey = $payload['cacheKey'];
        $paginate = $payload['paginate'];
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search, $paginate) {
            return $this->model::with(['profile'])->filter($search)->paginate($paginate);
        });
    }

    /**
     * Get Data Lates.
     */
    public function getUserWithProfileLatest(string $id): object
    {
        return $this->model::with(['profile'])->latest()->first();
    }

    /**
     * Show.
     */
    public function getUserWithProfile(string $id): object
    {
        return $this->model::with(['profile'])->find($id);
    }
}

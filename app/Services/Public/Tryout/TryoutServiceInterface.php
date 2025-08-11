<?php

namespace App\Services\Public\Tryout;

use App\DataTransferObjects\TryoutDTO;

interface TryoutServiceInterface
{
    public function getTryoutByEventId(array $paginate): object;
}

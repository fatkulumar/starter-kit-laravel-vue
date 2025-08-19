<?php

namespace App\Repositories\Student\Subtest;

use App\Enums\StatusTryoutEnum;
use App\Models\Subtest;
use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SubtestRepository extends Repository implements SubtestRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Subtest $model)
    {
        $this->model = $model;
    }

    /**
     * List all data pagninate.
     */
    public function getSubtestAndQuestionByTryoutId(array $payload): object
    {
        $cacheKey = $payload['cacheKey'];
        $minutes  = $payload['minutes'];
        $tryoutId   = $payload['tryout_id'];
        $userId   = $payload['user_id'];

        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($tryoutId, $userId) {
            $subtest = $this->model->join('start_tryouts', 'start_tryouts.tryout_id', '=', 'subtests.tryout_id')
                ->join('tryouts', 'tryouts.id', '=', 'subtests.tryout_id')
                ->with(['questions'])
                ->where('subtests.tryout_id', $tryoutId)
                ->where('start_tryouts.user_id', $userId)
                ->select(
                    'subtests.id',
                    'subtests.title',
                    'subtests.amount_question',
                    'subtests.amount_minutes',
                    'start_tryouts.start_at',
                    'start_tryouts.finish_at',
                    'tryouts.duration',
                    DB::raw('DATE_ADD(start_tryouts.start_at, INTERVAL tryouts.duration MINUTE) as end_at')
                )
                ->first();

            if ($subtest) {
                $startTime = Carbon::parse($subtest->start_at);
                $endTime   = Carbon::parse($subtest->end_at)->addMinutes(intval($subtest->duration ?? 0));
                $now       = now();

                if ($now->lt($startTime)) {
                    $subtest->status_tryout = StatusTryoutEnum::UNAVAILABLE->value;
                } elseif ($now->between($startTime, $endTime) && ($subtest->amount_question ?? 0) > 0) {
                    $subtest->status_tryout = StatusTryoutEnum::ACTIVE->value;
                } else {
                    $subtest->status_tryout = StatusTryoutEnum::EXPIRED->value;
                }
            }

            return $subtest;
        });
    }
}

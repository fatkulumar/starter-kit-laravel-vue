<?php

namespace App\Repositories\Student\Tryout;

use App\Enums\OrderStatusEnum;
use App\Enums\StatusTryoutDoingEnum;
use App\Enums\StatusTryoutEnum;
use App\Models\Tryout;
use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TryoutRepository extends Repository implements TryoutRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Tryout $model)
    {
        $this->model = $model;
    }

    /**
     * List all data pagninate.
     */
    public function getTryoutByEventId(array $payload): object
    {
        $cacheKey = $payload['cacheKey'];
        $event_id = $payload['event_id'];
        $minutes = $payload['minutes'];

        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($event_id) {
            return $this->model
                ->with(['orders:id,user_id,tryout_id', 'orders.user' => function ($query) {
                    $query->select('id')->setEagerLoads([]);
                }])
                ->where('event_id', $event_id)
                ->select('id', 'title', 'price')
                ->get(['title'])
                ->each(function ($item) {
                    $item->orders->each(function ($order) {
                        $order->user->makeHidden('role');
                    });
                });
        });
    }

    /**
     * get tryouts pruchased by event_id
     */
    public function getTryoutPurchasedByEventId(array $payload): \Illuminate\Support\Collection
    {
        $cacheKey = $payload['cacheKey'];
        $event_id = $payload['event_id'];
        $minutes  = $payload['minutes'];
        $userId   = $payload['user_id'];

        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($event_id, $userId) {
            return $this->model::join('orders', 'orders.tryout_id', '=', 'tryouts.id')
                ->join('events', 'events.id', '=', 'tryouts.event_id')
                ->leftJoin('subtests', 'subtests.tryout_id', '=', 'tryouts.id')
                ->leftJoin('start_tryouts', function ($join) use ($userId) {
                    $join->on('start_tryouts.tryout_id', '=', 'tryouts.id')
                        ->where('start_tryouts.user_id', '=', $userId);
                })
                ->where('orders.user_id', $userId)
                ->where('orders.status', OrderStatusEnum::PAID->value)
                ->where('tryouts.event_id', $event_id)
                ->select(
                    'tryouts.id as tryout_id',
                    'tryouts.title',
                    'tryouts.tryout_code',
                    'tryouts.start_time',
                    'tryouts.end_time',
                    DB::raw('COALESCE(SUM(subtests.amount_question),0) as amount_question'),
                    DB::raw('COALESCE(SUM(subtests.amount_minutes),0) as amount_minutes'),
                    'start_tryouts.start_at',
                    'start_tryouts.finish_at'
                )
                ->groupBy(
                    'tryouts.id',
                    'tryouts.title',
                    'tryouts.tryout_code',
                    'tryouts.start_time',
                    'tryouts.end_time',
                    'start_tryouts.start_at',
                    'start_tryouts.finish_at'
                )
                ->get()
                ->map(function ($tryout) {
                    $startTime = Carbon::parse($tryout->start_time);
                    $endTime   = Carbon::parse($tryout->end_time)->addMinutes($tryout->total_minutes ?? 0);
                    $now       = now();

                    if ($now->lt($startTime) || ($tryout->amount_question ?? 0) == 0) {
                        $tryout->status_tryout = StatusTryoutEnum::UNAVAILABLE->value; // belum mulai
                    } elseif ($now->between($startTime, $endTime) && ($tryout->amount_question ?? 0) > 0) {
                        $tryout->status_tryout = StatusTryoutEnum::ACTIVE->value; // sedang berlangsung
                    } else {
                        $tryout->status_tryout = StatusTryoutEnum::EXPIRED->value; // sudah lewat
                    }

                    // status user
                    if (!$tryout->start_at && !$tryout->finish_at) {
                        $tryout->status_user = StatusTryoutDoingEnum::STAY->value;
                    } elseif ($tryout->start_at && !$tryout->finish_at) {
                        $tryout->status_user = StatusTryoutDoingEnum::DOING->value;
                    } else {
                        $tryout->status_user = StatusTryoutDoingEnum::DONE->value;
                    }

                    return $tryout;
                });
        });
    }

    /**
     * Get tryout by tryout_code has order paid.
     */
    public function getTryoutByTryoutCode(array $payload): ?object
    {
        $tryout_code = $payload['tryout_code'];
        $userId      = $payload['user_id'];

        $tryout = $this->model::join('orders', 'orders.tryout_id', '=', 'tryouts.id')
            ->join('events', 'events.id', '=', 'tryouts.event_id')
            ->leftJoin('subtests', 'subtests.tryout_id', '=', 'tryouts.id')
            ->leftJoin('start_tryouts', function ($join) use ($userId) {
                $join->on('start_tryouts.tryout_id', '=', 'tryouts.id')
                    ->where('start_tryouts.user_id', '=', $userId);
            })
            ->where('orders.status', OrderStatusEnum::PAID->value)
            ->where('tryouts.tryout_code', $tryout_code)
            ->select(
                'tryouts.id',
                'tryouts.title',
                'tryouts.tryout_code',
                'tryouts.start_time',
                'tryouts.end_time',
                DB::raw('COALESCE(SUM(subtests.amount_question),0) as amount_question'),
                DB::raw('COALESCE(SUM(subtests.amount_minutes),0) as amount_minutes'),
                'start_tryouts.start_at',
                'start_tryouts.finish_at'
            )
            ->groupBy(
                'tryouts.id',
                'tryouts.title',
                'tryouts.tryout_code',
                'tryouts.start_time',
                'tryouts.end_time',
                'start_tryouts.start_at',
                'start_tryouts.finish_at'
            )
            ->first();

        if ($tryout) {
            $startTime = Carbon::parse($tryout->start_time);
            $endTime   = Carbon::parse($tryout->end_time)->addMinutes(intval($tryout->amount_minutes ?? 0));
            $now       = now();

            if ($now->lt($startTime) || ($tryout->amount_question ?? 0) == 0) {
                $tryout->status_tryout = StatusTryoutEnum::UNAVAILABLE->value;
            } elseif ($now->between($startTime, $endTime) && ($tryout->amount_question ?? 0) > 0) {
                $tryout->status_tryout = StatusTryoutEnum::ACTIVE->value;
            } else {
                $tryout->status_tryout = StatusTryoutEnum::EXPIRED->value;
            }

            if (!$tryout->start_at && !$tryout->finish_at) {
                $tryout->status_user = StatusTryoutDoingEnum::STAY->value;
            } elseif ($tryout->start_at && !$tryout->finish_at) {
                $tryout->status_user = StatusTryoutDoingEnum::DOING->value;
            } else {
                $tryout->status_user = StatusTryoutDoingEnum::DONE->value;
            }
        }

        return $tryout;
    }

    /**
     * Get Question by tryout_id.
     */
    public function getQuestionByTryoutId(array $payload): object
    {
        $cacheKey = $payload['cacheKey'];
        $minutes  = $payload['minutes'];
        $tryoutId   = $payload['tryout_id'];
        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($tryoutId) {
            return $this->model::join('subtests', 'subtests.tryout_id', '=', 'tryouts.id')
                ->where('tryout_id', $tryoutId)
                ->get();
        });
    }
}

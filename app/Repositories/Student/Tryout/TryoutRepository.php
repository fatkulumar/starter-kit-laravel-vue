<?php

namespace App\Repositories\Student\Tryout;

use App\Enums\OrderStatusEnum;
use App\Models\Tryout;
use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

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
    public function getTryoutPurchasedByEventId(array $payload): object
    {
        $cacheKey = $payload['cacheKey'];
        $event_id = $payload['event_id'];
        $minutes  = $payload['minutes'];
        $userId   = $payload['user_id']; // tambahkan user_id di payload

        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($event_id, $userId) {
            return $this->model::join('orders', 'orders.tryout_id', '=', 'tryouts.id')
                ->join('events', 'events.id', '=', 'tryouts.event_id')
                ->join('subtests', 'subtests.tryout_id', '=', 'tryouts.id')
                ->leftJoin('start_tryouts', function ($join) use ($userId) {
                    $join->on('start_tryouts.tryout_id', '=', 'tryouts.id')
                        ->where('start_tryouts.user_id', '=', $userId);
                })
                ->where('orders.status', OrderStatusEnum::PAID->value)
                ->where('tryouts.event_id', $event_id)
                ->select(
                    'tryouts.id',
                    'tryouts.title',
                    'tryouts.start_time',
                    'tryouts.tryout_code',
                    'events.end_time',
                    'subtests.amount_question',
                    'subtests.amount_minutes',
                    'start_tryouts.start_at',
                    'start_tryouts.finish_at'
                )
                ->get()
                ->map(function ($tryout) {
                    $endTime   = Carbon::parse($tryout->end_time);
                    $endActive = $endTime->copy()->addMinutes($tryout->amount_minutes); // duration = menit
                    $now       = now();

                    // status tryout (unavailable/active/expired)
                    if ($now->lt($endTime)) {
                        $tryout->status_tryout = 'UNAVAILABLE';
                    } elseif ($now->between($endTime, $endActive)) {
                        $tryout->status_tryout = 'ACTIVE';
                    } else {
                        $tryout->status_tryout = 'EXPIRED';
                    }

                    // status user
                    if (!$tryout->start_at && !$tryout->finish_at) {
                        $tryout->status_user = 'FINISHED'; // belum ada record = anggap finished
                    } elseif ($tryout->start_at && !$tryout->finish_at) {
                        $tryout->status_user = 'DOING';
                    } else {
                        $tryout->status_user = 'done';
                    }

                    return $tryout;
                });
        });
    }

    /**
     * Get tryout by tryout_code
     */
    public function getTryoutByTryoutCode(array $payload): ?object
    {
        $tryout_code = $payload['tryout_code'];
        $userId      = $payload['user_id'];

        $tryout = $this->model::join('orders', 'orders.tryout_id', '=', 'tryouts.id')
            ->join('events', 'events.id', '=', 'tryouts.event_id')
            ->join('subtests', 'subtests.tryout_id', '=', 'tryouts.id')
            ->leftJoin('start_tryouts', function ($join) use ($userId) {
                $join->on('start_tryouts.tryout_id', '=', 'tryouts.id')
                    ->where('start_tryouts.user_id', '=', $userId);
            })
            ->where('orders.status', OrderStatusEnum::PAID->value)
            ->where('tryouts.tryout_code', $tryout_code)
            ->select(
                'tryouts.id',
                'tryouts.title',
                'tryouts.start_time',
                'tryouts.tryout_code',
                'tryouts.duration',
                'events.end_time',
                'subtests.amount_question',
                'subtests.amount_minutes',
                'start_tryouts.start_at',
                'start_tryouts.finish_at'
            )
            ->first(); // langsung ambil satu

        if ($tryout) {
            $endTime   = Carbon::parse($tryout->end_time);
            $endActive = $endTime->copy()->addMinutes($tryout->amount_minutes);
            $now       = now();

            // Tentukan status tryout berdasarkan waktu sekarang
            if ($now->lt($endTime)) {
                // Jika waktu sekarang masih lebih kecil dari waktu mulai ($endTime),
                // maka tryout belum bisa dikerjakan → status "unavailable"
                $tryout->status_tryout = 'unavailable';
            } elseif ($now->between($endTime, $endActive)) {
                // Jika waktu sekarang berada di antara waktu mulai ($endTime)
                // sampai waktu berakhir ($endActive), maka tryout sedang aktif → status "active"
                $tryout->status_tryout = 'active';
            } else {
                // Jika waktu sekarang sudah lewat dari waktu berakhir ($endActive),
                // maka tryout sudah selesai → status "expired"
                $tryout->status_tryout = 'expired';
            }


            // status user
            if (!$tryout->start_at && !$tryout->finish_at) {
                $tryout->status_user = 'stay'; // belum ada record = anggap finished
            } elseif ($tryout->start_at && !$tryout->finish_at) {
                $tryout->status_user = 'doing';
            } else {
                $tryout->status_user = 'done';
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

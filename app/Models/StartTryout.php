<?php

namespace App\Models;

use App\Traits\HashUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StartTryout extends Model
{
    use HasFactory, HashUuid;

    protected $fillable = [
        'tryout_id',
        'user_id',
        'start_at',
        'finish_at'
    ];

    /**
     * Relation to tryout.
     */
    public function tryout(): BelongsTo
    {
        return $this->belongsTo(Tryout::class);
    }

     /**
     * Relation to tryout.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

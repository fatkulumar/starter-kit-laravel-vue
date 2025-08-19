<?php

namespace App\Models;

use App\Traits\HashUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use HasFactory, HashUuid;

    protected $fillable = [
        'user_id',
        'subtest_id',
        'question_id',
        'answer',
    ];

    /**
     * Relation to subtest
     */
    public function subtest(): BelongsTo
    {
        return $this->belongsTo(Subtest::class);
    }
}

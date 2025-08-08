<?php

namespace App\Models;

use App\Traits\HashUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HashUuid, HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'subject_id',
        'subtest_id',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'option_e',
        'explanation',
        'correct_answer'
    ];

    /**
     * Relation to subject
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relation to subtest
     */
    public function subtest(): BelongsTo
    {
        return $this->belongsTo(Subtest::class);
    }
}

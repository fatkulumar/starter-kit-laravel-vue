<?php

namespace App\Models;

use App\Traits\HashUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subtest extends Model
{
    use HashUuid, HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tryout_id',
        'title',
        'amount_question',
        'amount_minutes'
    ];

    /**
     * Filter
     */
    public function scopeFilter($query, $search)
    {
        $query->when($search, function ($q) use ($search) {
            $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%']);
        });
    }

    /**
     * Relation to tryouts.
     */
    public function tryout(): BelongsTo
    {
        return $this->belongsTo(Tryout::class);
    }
}

<?php

namespace App\Models;

use App\Traits\HashUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Subtest extends Model
{
    use HashUuid, HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tryout_id',
        'subtest_code',
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

    /**
     * Generate subtest code.
     */
    protected static function booted(): void
    {
        static::creating(function ($subtest) {
            if (empty($subtest->subtest_code)) {
                do {
                    $subtest_code = 'subtest-' . Carbon::now()->format('Ymd') . '-' . strtoupper(Str::random(6));
                } while (self::where('subtest_code', $subtest_code)->exists());

                $subtest->subtest_code = $subtest_code;
            }
        });
    }
}

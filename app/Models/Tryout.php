<?php

namespace App\Models;

use App\Traits\FileUpload;
use App\Traits\HashUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tryout extends Model
{
    use HasFactory, HashUuid, FileUpload;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'tryout_code',
        'grade_id',
        'thumbnail',
        'title',
        'description',
        'start_time',
        'end_time',
        'duration',
        'is_active',
        'is_locked',
        'guide_link',
        'price',
    ];
    protected $appends = [
        'thumbnail_url',
        'start_time_formatted',
        'end_time_formatted',
    ];

    /**
     * Filtering
     */
    public function scopeFilter($query, $search)
    {
        $query->when($search, function ($q) use ($search) {
            $q->whereRaw('LOWER(title) LIKE ?', ["%" . strtolower($search) . "%"]);
        });
    }

    /**
     * Trait FileUpload
     */
    protected function fileSettings(): void
    {
        $this->settings = [
            'attributes'  => ['jpeg', 'jpg', 'png'],
            'path'        => 'upload/tryout/thumbnail/',
            'softdelete'  => false
        ];
    }

    /**
     * Relation to event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Accessor thumbnail_url
     */
    public function getThumbnailUrlAttribute(): string | null
    {
        $this->fileSettings();
        return $this->thumbnail
            ? asset($this->settings['path'] . $this->thumbnail)
            : null;
    }

    /**
     * Accessor start_time_formatted
     */
    public function getStartTimeFormattedAttribute(): string
    {
        return Carbon::parse($this->start_time)->translatedFormat('l, j F Y');
    }

    /**
     * Accessor end_time_formatted
     */
    public function getEndTimeFormattedAttribute(): string
    {
        return Carbon::parse($this->end_time)->translatedFormat('l, j F Y');
    }

    /**
     * Relation to event
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Create code tryout
     */
    protected static function booted(): void
    {
        static::creating(function ($tryout) {
            if (empty($tryout->tryout_code)) {
                do {
                    $tryout_code = 'tryout-' . Carbon::now()->format('Ymd') . '-' . strtoupper(Str::random(6));
                } while (self::where('tryout_code', $tryout_code)->exists());

                $tryout->tryout_code = $tryout_code;
            }
        });
    }

    /**
     * Relation to orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relation to users
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'orders');
    }

    // without append
    public function withoutAppends(array $except = []): static
    {
        $appends = array_diff($this->getAppends(), $except);
        return $this->setAppends($appends);
    }
}

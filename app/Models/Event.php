<?php

namespace App\Models;

use App\Traits\HashUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, HashUuid;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $appends = [
        'banner_url',
        'start_time_formatted',
        'end_time_formatted',
        'registration_deadline_formatted',
        'registration_end_formatted',
        'preliminary_date_formatted',
        'final_date_formatted'
    ];
    protected $fillable = [
        'title',
        'description',
        'banner',
        'start_time',
        'end_time',
        'registration_deadline',
        'registration_end',
        'preliminary_date',
        'final_date',
        'whatsapp_group_link',
        'guidebook_link',
        'location',
        'link_zoom',
        'quota',
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
     * Relation to tryout
     */
    public function tryouts()
    {
        return $this->hasMany(Tryout::class);
    }


    /**
     * Trait FileUpload
     */
    protected function fileSettings(): void
    {
        $this->settings = [
            'attributes'  => ['jpeg', 'jpg', 'png'],
            'path'        => 'upload/event/thumbnail/',
            'softdelete'  => false
        ];
    }

    /**
     * Accessor banner_url
     */
    public function getBannerUrlAttribute(): string | null
    {
        $this->fileSettings();
        return $this->banner
            ? asset($this->settings['path'] . $this->banner)
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
     * Accessor registration_deadline_formatted
     */
    public function getRegistrationDeadlineFormattedAttribute(): string
    {
        return Carbon::parse($this->registration_deadline)->translatedFormat('l, j F Y');
    }

    /**
     * Accessor registration_end_formatted
     */
    public function getRegistrationEndFormattedAttribute(): string
    {
        return Carbon::parse($this->registration_end)->translatedFormat('l, j F Y');
    }

    /**
     * Accessor preliminary_date_formatted
     */
    public function getPreliminaryDateFormattedAttribute(): string
    {
        return Carbon::parse($this->preliminary_date)->translatedFormat('l, j F Y');
    }

    /**
     * Accessor final_date_formatted
     */
    public function getFinalDateFormattedAttribute(): string
    {
        return Carbon::parse($this->final_date)->translatedFormat('l, j F Y');
    }
}

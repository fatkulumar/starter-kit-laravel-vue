<?php

namespace App\Models;

use App\Traits\FileUpload;
use App\Traits\HashUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HashUuid, FileUpload;
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'photo'
    ];

    protected $appends = ['photo_url'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Trait FileUpload
     */
    protected function fileSettings(): void
    {
        $this->settings = [
            'attributes'  => ['jpeg', 'jpg', 'png'],
            'path'        => 'upload/profile/',
            'softdelete'  => false
        ];
    }

    public function getPhotoUrlAttribute(): string
    {
        $this->fileSettings();
        return $this->photo
            ? asset($this->settings['path'] . $this->photo)
            : null;
    }
}

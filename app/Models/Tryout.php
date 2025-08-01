<?php

namespace App\Models;

use App\Traits\HashUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tryout extends Model
{
    use HasFactory, HashUuid;

    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'event_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'duration',
        'is_active',
        'is_locked',
        'cover_image',
        'guide_link',
    ];
}

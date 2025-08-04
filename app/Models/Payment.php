<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = false;

    protected $fillable = [
        'order_id',
        'payment_gateway',
        'payment_method',
        'reference',
        'amount_paid',
        'status',
        'raw_response'
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];
}

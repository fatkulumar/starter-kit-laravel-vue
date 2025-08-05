<?php

namespace App\Models;

use App\Enums\PaymentGatewayEnum;
use App\Enums\PaymentMethodEnum;
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
        'payment_gateway' => PaymentGatewayEnum::class,
        'payment_method' => PaymentMethodEnum::class,
        'order'
    ];

    /**
     * Relation to order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

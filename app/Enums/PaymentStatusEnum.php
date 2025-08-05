<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case AUTHORIZE = 'authorize';
    case CAPTURE = 'capture';
    case SETTLEMENT = 'settlement';
    case PENDING = 'pending';
    case DENY = 'deny';
    case CANCEL = 'cancel';
    case EXPIRE = 'expire';
    case REFUND = 'refund';
    case PARTIAL_REFUND = 'partial_refund';
    case CHARGEBACK = 'chargeback';
    case PARTIAL_CHARGEBACK = 'partial_chargeback';
    case FAILURE = 'failure';
}

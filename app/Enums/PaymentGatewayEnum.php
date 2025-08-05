<?php

namespace App\Enums;

enum PaymentGatewayEnum: string
{
    case MIDTRANS = 'midtrans';
    case TRIPAY = 'tripay';
    case XENDIT = 'xendit';
    case ADMIN = 'admin';
}

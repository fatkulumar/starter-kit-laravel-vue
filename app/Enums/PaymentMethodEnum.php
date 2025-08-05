<?php

namespace App\Enums;

enum PaymentMethodEnum: string
{
    case GOPAY = 'gopay';
    case BCA_VA = 'bca_va';
    case QRIS = 'qris';
    case MANDIRI_VA = 'mandiri_va';
    case OVO = 'ovo';
    case BRI_VA = 'bri_va';
    case ADMIN = 'admin';
}

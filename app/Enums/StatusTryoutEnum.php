<?php

namespace App\Enums;

enum StatusTryoutEnum: string
{
    case UNAVAILABLE = 'unavailable';
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
}

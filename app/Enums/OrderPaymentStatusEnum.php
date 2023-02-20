<?php

namespace App\Enums;

enum OrderPaymentStatusEnum: string
{
    case SUCCESSFUL = 'successful';
    case FAILED = 'failed';
    case PENDING = 'pending';

}

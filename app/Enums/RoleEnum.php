<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = 'SUPER_ADMIN';
    case ADMIN = 'ADMIN'
    case SALES_MANAGER = 'SALES_MANAGER';
    case SALES_STAFF = 'SALES_STAFF';
}

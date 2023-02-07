<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SuperAdmin = 'store owner';
    case Admin = 'System admin';
    case SALESMAN = 'sales manager';
    case SALESSTAFF = 'sales staff';
}

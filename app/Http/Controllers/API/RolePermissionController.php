<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    private $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }
    public function assignPermissionToRole()
    {
        $this->service->assignPermissionToRole();
        return response('', 201);
    }

    public function assignRoleToUser()
    {
        $this->service->assignRoleToUser();
        return response('', 201);
    }
}

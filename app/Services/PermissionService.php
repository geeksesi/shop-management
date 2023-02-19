<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class PermissionService
{
    public function assignPermissionToRole()
    {
        $enumsArr = RoleEnum::cases();
        $roles = array_column($enumsArr, 'value');
        $permissionIds = Permission::pluck('id')->toArray();

        foreach($roles as $role){
            $role = Role::findByName($role);
           if ($role->name == 'SUPER_ADMIN'){
              return $role->permissions()->attach($permissionIds);
           }
           if ($role->name == 'ADMIN'){
               $ids= [$permissionIds[0],$permissionIds[1],$permissionIds[2],$permissionIds[3]];
               return $role->permissions()->attach($ids);
           }
           if ($role->name =='SALES_MANAGER'){
               $ids= [$permissionIds[1],$permissionIds[3]];
               return $role->permissions()->attach($ids);
           }
               $ids= [$permissionIds[1]];
                return $role->permissions()->attach($ids);

        }

    }

    public function assignRoleToUser()
    {
       $superAdmin =  User::query()->first();
       $roles = Role::all();
        $superAdmin->roles()->attach($roles[0]['id']); //avalin user super-admin
        $adminUser = User::skip(1)->first();
        $adminUser->roles()->attach($roles[1]['id']);
        $salesManager = User::skip(2)->first();
        $salesManager->roles()->attach($roles[2]['id']);
        $salesManager = User::skip(3)->first();
        $salesManager->roles()->attach($roles[3]['id']);
    }
}

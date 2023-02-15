<?php

namespace Tests\Feature\Models;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testRoleRelationshipWithUser()
    {
        $count= rand(0,10);
        $role = Role::factory()
        ->hasUsers($count)->create();

        $this->assertCount($count,$role->users);
        $this->assertTrue($role->users->first() instanceof User);
    }

    public function testRoleRelationshipWithPermission()
    {
        $count= rand(0,10);
        $role = Role::factory()
        ->hasPermissions($count)->create();

        $this->assertCount($count,$role->permissions);
        $this->assertTrue($role->permissions->first() instanceof Permission);
    }
}

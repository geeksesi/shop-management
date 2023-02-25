<?php

namespace Tests\Feature\Models;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPermissionRelationshipWithRole()
    {
        $count= rand(1,10);
        $permission = Permission::factory()
        ->hasRoles($count)->create();

        $this->assertCount($count,$permission->roles);
        $this->assertTrue($permission->roles->first() instanceof Role);
    }
}

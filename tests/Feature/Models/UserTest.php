<?php

namespace Tests\Feature\Models;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testUserRelationshipWithRole()
    {
        $count= rand(1,10);
        $user = User::factory()
        ->hasRoles($count)->create();

        $this->assertCount($count,$user->roles);
        $this->assertTrue($user->roles->first() instanceof Role);
    }
}

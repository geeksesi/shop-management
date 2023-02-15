<?php

namespace Tests\Feature\Middelwares;

use http\Client\Curl\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckUserIsAdminMiddelwareTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testWhenUserIsNotAdmin()
    {
       $user = User::factory()->state(['type'=>'user'])->create();
    }
}

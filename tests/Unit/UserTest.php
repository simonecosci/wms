<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserTest extends TestCase {

    use RefreshDatabase;
    use WithFaker;
    use WithoutMiddleware;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample() {
        $this->assertTrue(true);
    }

    public function testCreate() {
        $user = factory('App\User')->create();
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }
    
    public function testAuthentication() {
        $user = factory('App\User')->create();
        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);
    }

}

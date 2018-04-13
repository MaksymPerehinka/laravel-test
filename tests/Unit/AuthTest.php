<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_creates_inactive_user_after_registration()
    {
        $user = factory('App\User')->make()->toArray();
        $user['password_confirmation'] = $user['password'] = str_random(8);

        $this->post('/register', $user);

        $user = User::first();
        $this->assertFalse(boolval($user->confirmed));
    }
    
    /** @test */
    public function it_sends_confirmation_email_after_registration()
    {
        // given we make a registration

        // assert that email is send to users email
    }

    /** @test */
    public function confirmed_user_can_login()
    {
        $password = str_random(8);
        $user = factory('App\User')->create([
            'password' => Hash::make($password),
            'confirmed' => true
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => $password
        ]);

        $this->assertTrue(auth()->check());
    }

    /** @test */
    public function unconfirmed_user_can_not_login()
    {
        $password = str_random(8);
        $user = factory('App\User')->create([
            'password' => Hash::make($password)
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => $password
        ]);

        $this->assertFalse(auth()->check());
    }
}
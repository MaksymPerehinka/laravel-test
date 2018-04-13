<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ConfirmationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function confirmation_token_creates_after_user_registration()
    {
        $user = $this->registerUser();

        $this->assertNotNull($user->confirmationToken);
    }

    /** @test */
    public function confirmation_token_deletes_after_user_confirmation()
    {
        $user = factory('App\User')->create();
        $token = md5(str_random(6));
        $user->confirmationToken()->create(['hash' => $token]);

        $this->get(route('user.confirm', ['token' => $token]));

        $this->assertNull($user->confirmationToken);
    }

    /**
     * @return mixed
     */
    private function registerUser()
    {
        $user = factory('App\User')->make()->toArray();
        $user['password_confirmation'] = $user['password'] = str_random(8);

        $this->post('/register', $user);

        $user = User::first();
        return $user;
    }
}

<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ConfirmationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_confirm_his_registration()
    {
        $user = $this->registerUser();

        $this->get($user->getConfirmationLink())->assertStatus(200);

        $this->assertTrue(boolval($user->fresh()->confirmed));
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

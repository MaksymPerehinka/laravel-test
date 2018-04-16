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
        $user = registerUser($this);

        $this->assertNotNull($user->confirmationToken);
    }

    /** @test */
    public function confirmation_token_deletes_after_user_confirmation()
    {
        $token = md5(str_random(6));
        $user->confirmationToken()->create(['hash' => $token]);

        $this->get(route('user.confirm', ['token' => $token]));

        $this->assertNull($user->confirmationToken);
    }

    /** @test */
    public function unauthorized_user_can_not_request_a_confirmation_link()
    {
        $user = create('App\User', ['confirmed' => true]);

        $this->get(route('user.confirm.request_token', $user))
            ->assertStatus(403);

        $this->signIn();
        $this->get(route('user.confirm.request_token', auth()->user()))
            ->assertRedirect(route('home'));
    }
}
<?php

namespace Tests\Unit;

use App\ConfirmationToken;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_deletes_confirmation_token_on_deleting()
    {
        $user = factory('App\User')->create();
        $token = md5(str_random(6));
        $user->confirmationToken()->create(['hash' => $token]);

        $user->delete();

        $query = ConfirmationToken::where('hash', $token)->get();
        $this->assertEmpty($query);
    }

    /** @test */
    public function it_has_confirmation_token_when_not_confirmed()
    {
        $user = factory('App\User')->create();
        $token = md5(str_random(6));
        $user->confirmationToken()->create(['hash' => $token]);

        $this->assertInstanceOf('App\ConfirmationToken', $user->confirmationToken);
    }

    /** @test */
    public function unauthorized_user_can_not_confirm_it()
    {
        // given we have an authenticated user
        // he is redirected

        // given we have a user with wrong token
        // he is given error message
    }
}

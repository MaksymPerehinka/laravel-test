<?php

namespace Tests\Unit;

use App\ConfirmationToken;
use App\UserRegistrationLog;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_deletes_confirmation_token_on_deleting()
    {
        list($user, $token) = $this->createUserWithConfirmationToken();

        $user->delete();

        $query = ConfirmationToken::where('hash', $token)->get();
        $this->assertEmpty($query);
    }

    /** @test */
    public function it_has_confirmation_token_when_not_confirmed()
    {
        list($user, $token) = $this->createUserWithConfirmationToken();

        $this->assertInstanceOf('App\ConfirmationToken', $user->confirmationToken);
    }

    /** @test */
    public function unauthorized_user_can_not_confirm_an_account()
    {
        list($user, $token) = $this->createUserWithConfirmationToken();

        $this->get(route('user.confirm', ['token' => 'adfasfasf']))
            ->assertSee(__('user.confirm.error_wrong_token', ['uri' => route('home')]))
            ->assertStatus(422);

        $this->signIn();
        $this->get(route('user.confirm'))
            ->assertStatus(403);
    }

    /**
     * @return array
     */
    public function createUserWithConfirmationToken(): array
    {
        $user = create('App\User');
        $token = md5(str_random(6));
        $user->confirmationToken()->create(['hash' => $token]);
        return array($user, $token);
    }

    /** @test */
    public function it_can_have_a_registration_log_record()
    {
        $user = create('App\User');
        UserRegistrationLog::create(['user_id' => $user->id]);

        $this->assertInstanceOf('App\UserRegistrationLog', $user->registrationLogRecord);
    }
}
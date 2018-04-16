<?php

namespace Tests\Unit;

use App\Mail\RegistrationConfirmationEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_creates_inactive_user_after_registration()
    {
        $user = registerUser($this);

        $this->assertFalse(boolval($user->confirmed));
    }

    /** @test */
    public function a_confirmation_email_sends_upon_the_registration()
    {
        Mail::fake();

        event(new Registered(create('App\User')));

        Mail::assertSent(RegistrationConfirmationEmail::class);

        // TODO: test email text
    }

    /** @test */
    public function confirmed_user_can_login()
    {
        $password = str_random(8);
        $user = create('App\User', [
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
        $user = create('App\User', [
            'password' => Hash::make($password)
        ]);
        $flash_message = __('auth.error_confirmation', [
            'uri' => route('user.confirm.request_token', $user)
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => $password
        ])
            ->assertSessionHas('flash_message', $flash_message)
            ->assertSessionHas('flash_css_class', __('alert-danger'));

        $this->assertFalse(auth()->check());
    }

    /** @test */
    public function it_redirects_user_to_login_page_with_success_flash_message()
    {
        $user = make('App\User')->toArray();
        $user['password_confirmation'] = $user['password'] = str_random(8);

        $this->post('/register', $user)
            ->assertRedirect('login')
            ->assertSessionHas('flash_message', __('auth.text_successful_registration'))
            ->assertSessionHas('flash_css_class', __('alert-success'));
    }

    /** @test */
    public function users_registration_timestamp_is_logging()
    {
        $user = registerUser($this);

        $this->assertDatabaseHas('user_registration_logs', [
            'user_id' => $user->id
        ]);
    }
}
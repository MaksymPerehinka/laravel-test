<?php

namespace Tests\Feature;

use App\Mail\RegistrationConfirmationEmail;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ConfirmationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_confirm_his_registration()
    {
        $user = registerUser($this);

        $this->get($user->getConfirmationLink())
            ->assertSee(__('user.confirm.text_success', ['uri' => route('login')]));

        $this->assertTrue(boolval($user->fresh()->confirmed));
    }

    /** @test */
    public function a_user_can_get_confirmation_link()
    {
        Mail::fake();

        $user = registerUser($this);

        $this->get(route('user.confirm.request_token', $user))
            ->assertRedirect(route('login'))
            ->assertSessionHas('flash_message', __('auth.text_successful_confirmation_link_sent'))
            ->assertSessionHas('flash_css_class', __('alert-success'));

        Mail::assertSent(RegistrationConfirmationEmail::class);
    }
}
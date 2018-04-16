<?php

namespace App\Http\Controllers;

use App\ConfirmationToken;
use App\Mail\RegistrationConfirmationEmail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    // FIXME: move to ConfirmationController later
    public function confirm(Request $request)
    {
        // FIXME: maybe move to middleware or policies?
        if(auth()->check()) {
            abort(403);
        }

        $token = ConfirmationToken::where('hash', $request->token)->first();

        // FIXME: maybe move to policies?
        if( ! $token ) {
            $error = __('user.confirm.error_wrong_token', ['uri' => route('home')]);
            return response()->view('user.confirm', ['message' => $error], 422);
        }

        $user = $token->user;
        $user->confirm();

        $message = __('user.confirm.text_success', ['uri' => route('login')]);
        return view('user.confirm', ['message' => $message]);
    }

    // FIXME: move to ConfirmationController later
    public function requestConfirmationToken(User $user)
    {
        // FIXME: move to policies later, if it fill get more complex
        if($user->confirmed) {
            abort(403);
        }

        Mail::to($user)->send(new RegistrationConfirmationEmail($user));

        request()->session()->flash('flash_message', __('auth.text_successful_confirmation_link_sent'));
        request()->session()->flash('flash_css_class', 'alert-success');

        return redirect('login');
    }
}
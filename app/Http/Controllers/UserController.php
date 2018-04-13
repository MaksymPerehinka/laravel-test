<?php

namespace App\Http\Controllers;

use App\ConfirmationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function confirm(Request $request)
    {
        DB::transaction(function () use ($request) {
            $user = ConfirmationToken::where('hash', $request->token)->firstOrFail()->user;

            $user->confirmed = true;
            $user->save();

            $user->confirmationToken()->delete();

            return view('user.confirm');
        });
    }
}

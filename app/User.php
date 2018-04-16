<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'confirmed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot()
    {
        static::deleted(function($user) {
            $user->confirmationToken()->delete();
        });
    }

    public function getConfirmationLink()
    {
        return route('user.confirm', [
            'token' => $this->confirmationToken->hash
        ]);
    }

    public function confirmationToken()
    {
        return $this->hasOne('App\ConfirmationToken');
    }

    public function confirm()
    {
        $this->confirmed = true;
        $this->save();

        $this->confirmationToken()->delete();
    }
}
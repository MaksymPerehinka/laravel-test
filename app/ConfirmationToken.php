<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfirmationToken extends Model
{
    protected $fillable = ['hash'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

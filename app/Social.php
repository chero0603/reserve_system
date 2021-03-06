<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{

    protected $fillable = ['provider_name', 'provider_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

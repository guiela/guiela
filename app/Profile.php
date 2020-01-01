<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['user_id', 'user_avatar', 'provider_user_id', 'provider'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

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
        'name', 'email', 'password', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'provider_name', 'provider_id','password',
    ];

    public function algorithm() 
    {
        return $this->hasMany("App\Algorithm");
    }
    public function post()
    {
        return $this->hasMany("App\Post");
    }
    public function role()
    {
        return $this->hasOne("App\Role");
    }
}

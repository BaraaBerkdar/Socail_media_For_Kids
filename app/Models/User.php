<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable implements JWTSubject
{

    protected $table = 'users';
    public $timestamps = false;

    
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'gender',
        'ssn',
        'role_id',
        'password'
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        "email_verified_at",
        "created_at",
        "updated_at",
        'password',
        'remember_token'
        ];



    public function hasChiled()
    {
        return $this->hasMany('App\Models\Child', 'user_id');
    }

    public function hasPosts()
    {
        return $this->hasMany('App\Models\Post', 'user_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


}
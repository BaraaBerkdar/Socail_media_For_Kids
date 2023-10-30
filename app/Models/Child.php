<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Child  extends Authenticatable implements JWTSubject
{

    protected $table = 'childs';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'user_id',
        'gender',
        'password' , 
        'profile' , 
        'minute' ,
        "day",
        "current"
    ];

    protected $hidden = [
        
        "created_at",
        "updated_at",
        'password',
        
        ];

    public function hasCategory()
    {
        return $this->hasMany('App\Models\Category_child', 'child_id');
    }

    public function hasRecord()
    {
        return $this->hasOne('App\Models\RecordsPosts', 'child_id');
    }

    public function hasTime()
    {
        return $this->hasMany('App\Models\Time', 'child_id');
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
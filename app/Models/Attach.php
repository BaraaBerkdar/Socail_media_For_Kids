<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attach extends Model 
{

    protected $table = 'attachments';
    public $timestamps = false;
    protected $fillable = [
       'id',
       'name',
       'post_id'

    ];

}
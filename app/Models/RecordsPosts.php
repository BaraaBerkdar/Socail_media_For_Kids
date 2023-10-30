<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordsPosts extends Model 
{
    protected $fillable = [
    "child_id"	,"post_id",	"comments",	"reaction"
        
    ];
    protected $hidden = [
        "has_record"
    ];

    protected $table = 'record_post';
    public $timestamps = false;

}
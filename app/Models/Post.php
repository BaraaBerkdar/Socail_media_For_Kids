<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model 
{

    protected $table = 'posts';
    public $timestamps = false;
    protected $fillable = [
        "caption" ,
        'date'  ,  
        'status' ,
        'category_id' ,
        'user_id'    , 
    ];
    protected $hidden = [

        "belongtocategory",
        "hasWriter",
        "user_id",
        "hasRecord",
        "category_id",
        "status",
        "has_attachment",
        "created_at"
    ];

    public function has_attachment()
    {
        return $this->hasMany('App\Models\Attach', 'post_id');
    }

    public function hasRecord()
    {
        return $this->hasMany('App\Models\RecordsPosts', 'post_id');
    }
    public function hasWriter(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function belongtocategory(){
        return $this->belongsTo("App\Models\Category","category_id");
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model 
{

    protected $table = 'category';
    public $timestamps = false;

    protected $fillable = [
      "name",
      "descreption"
    ];


    public function hasChild()
    {
        return $this->hasMany('App\Models\Category_child', 'category_id');
    }

}
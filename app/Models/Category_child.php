<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category_child extends Model 
{

    protected $table = 'category_child';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'child_id'
      ];
  


}
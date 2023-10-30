<?php

namespace App\Trait;



Trait uplodeimage 
{
  public function uplode($image,$path){
    $exd=$image->getClientOriginalExtension();
      //  $temp=explode(".",$image);
      //  $exd=end($temp);
        $name=time().'.'.$exd;
        $paths=$path;
        $image->move($paths,$name);
        return $name;


  }
}

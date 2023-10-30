<?php

namespace App\Trait;

/**
 * 
 */
trait messageResp
{
    
    public function data_res($key,$value,$msg=null){
        return response()->json([
            'status' =>'true',
            'key'    =>$key,
            'value'  =>$value,
            "msg"    =>$msg
        ]);

    }
    public function error($msg){
        return response()->json([
            'status' =>'false',
            "msg"    =>$msg
        ]);

    }
    public function sucsess($msg){
        return response()->json([
            'status' =>'true',
            "msg"    =>$msg
        ]);
    }

}

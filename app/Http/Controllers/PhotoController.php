<?php

namespace App\Http\Controllers;

use App\Models\Category_child ; 

use App\Models\Child;
use App\Models\Post;
use App\Models\User;
use App\Models\RecordsPosts ; 
use App\Models\Time;
use Illuminate\Http\Request;
// add validator
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Internal\ReturnTypeContract;
use Illuminate\Support\Facades\Hash ;
use App\Models\Category;
class PhotoController extends Controller
{
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|alpha:ascii',
            'email' => 'required|unique:childs,email',
            'password' => 'required',
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'msg' => $validator->errors(),
                "status" => "false",
            ], 400);
        }
        $totalMinutes = $request->input('minute')  ; 
        $totalHour = $request->input('hour')  ; 
        $totalHour *= 60  ; 
        $totalTime = $totalHour + $totalMinutes ;
        $image = $request->file('profile') ;
        $newName = "empty" ;
        if($request->hasFile('profile')){
            $newName = rand().'.'.$image->getClientOriginalExtension() ; 
            $image->move(public_path('/uploads/images') , $newName) ; 
            $newName = "/uploads/images/".$newName ; 
        }
        $pass = bcrypt($request->input('password')) ; 
        // return response()->json($newName) ; 

        $child = Child::create([
            'name' => $request->input('name') , 
            'profile' => $newName , 
            'minute' => $totalTime ,
            'email' => $request->input('email')  , 
            'gender'=>$request->input('gender') , 
            'password'=> $pass , 
            'user_id'=>$request->input('user_id')
        ]);
        
        $childId = $child->id ; 
        // $arr = ($request->input('cate_arr'));
        $arr = Category::select('id') ->get(); 
        // return $arr ; 
        foreach ($arr as $ele) {
            // echo $ele['id'] ; 
            $data = Category_child::create([
                'category_id' => $ele['id'],
                'child_id' => $childId
            ]);
        }

        return response()->json([
            "status" => "true",
            "key" => "INSETED CHILD",
            'message' => 'Data inserted successfully!'
        ], 201);
        // return "hhhiii" ; 
        // $validator = Validator::make($request->all(), [
        //     'photo' => 'required|image|mimes:jpeg,png,jpg,gif', // Adjust allowed image formats as needed
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 400);
        // }

        // if ($request->hasFile('photo')) {
        //     $image = $request->file('photo') ;
        //     $newName = "empty" ;
        //     $nameOfImage = $newName ; 
        //     if($request->hasFile('photo')){
        //         $newName = rand().'.'.$image->getClientOriginalExtension() ; 
        //         $image->move(public_path('/uploads/images') , $newName) ; 
        //         $nameOfImage = $newName ; 
        //         $newName = "/uploads/images/".$newName ; 
        //     }
        //     // echo $newName ;
        //     // $id  = 100 ; 
        //     // $id = $request->input('id') ;
        //     // echo $id ; 
        //     // if($id){
        //     //     $id = 6 ; 
        //     // }
        //     Attach::create([
        //         'name'=> $nameOfImage  , 
        //         'post_id' => $request->input('id') 
        //     ]);
        //     return response()->json(['message' => 'Photo uploaded successfully']);
        // }

        // return response()->json(['message' => 'No photo uploaded'], 400);
    }
}
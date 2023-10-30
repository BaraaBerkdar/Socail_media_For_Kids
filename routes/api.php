<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\PhotoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


################ Start  admin routing   #####################
Route::group(["prefix"=>"admin"],function(){
   
    Route::post('add_parent',[AdminController::class,'addParent']);
    Route::post("add_writer",[AdminController::class,'add_writer']);
    Route::post("add_checker",[AdminController::class,'add_checker']);
    Route::get("get_all_parent",[AdminController::class,'get_all_parent']);
    Route::get("get_all_writer",[AdminController::class,'get_all_writer']);
    Route::get("get_all_checker",[AdminController::class,'get_all_checker']);




});




################ End    admin  routing   #####################


################ Start  Post routing   #####################
Route::group(['namespace'=>"posts","prefix"=>"post"],function(){

    Route::post('cheked',[PostController::class,'index']);
    Route::get('pin',[PostController::class,'getpostespin']);
    Route::post('attachments',[PostController::class,'getattachment']);
    Route::post('get_posts_for_writer',[PostController::class,'getPostForWriter']);
    Route::post('add',[PostController::class,'store']);
    Route::post('delete',[PostController::class,'destroy']);
    Route::post('update',[PostController::class,'update']);
    Route::post('accept',[PostController::class,'accept']);
    Route::post('add_comment',[PostController::class,'addComment']);
    Route::get('comment/{post_id}',[PostController::class,'getComment']);
    Route::post('show',[PostController::class,'postShow']);
    Route::post('add_reaction',[PostController::class,'addreaction']);
    Route::get('reaction',[PostController::class,"get_reaction"]);

    Route::post('category',[PostController::class,'get_postes_for_category']);
});


################ End    Post  routing  #####################

##################### category ##################
Route::get("category",[PostController::class,'get_category']);

################ start  login an register routing ########
Route::post('login',[AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logout']);
Route::post('userinfo',[AuthController::class,'userinfo']);
Route::post('user_regiser',[AuthController::class,'register_user']);
Route::post('child_regiser',[AuthController::class,'register_child']);

################ end  login an register routing ########
Route::post('image',[PostController::class,'uplode']);
Route::post('check' , [AuthController::class , 'check']) ; 













// Route::get('/getChild' , [ChildController::class , 'index']  ); 
Route::get('/getAllChild/{id}' , [ChildController::class, 'show']) ; // done 
Route::post('/getChild' , [ChildController::class, 'showChild']) ; // done

Route::post('/insetChild' , [ChildController::class, 'store']) ; // done
Route::post('/getChildPosts' , [ChildController::class, 'showChildPosts']) ; // done
Route::get('/getChildComments' , [ChildController::class, 'showComments']) ;  // done 
Route::get('/getChildCategory' , [ChildController::class, 'showCategory']) ;  // done 
Route::post('/updateChild' , [ChildController::class, 'updateChild']) ; 
Route::post('/upload'  ,[PhotoController::class , 'upload' ] ) ; 
Route::post('/getProfile' , [ChildController::class , 'childProfile']) ; 
Route::post('/currentTime' , [PostController::class , 'currentTime']) ; 

// Route::post('/testCat', [CategoryController::class , 'test'] ) ;
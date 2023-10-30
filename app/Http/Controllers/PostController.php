<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Child;
use App\Models\Attach;
use  App\Trait\messageResp;
use  App\Trait\uplodeimage;
use App\Models\Category;
use App\Models\RecordsPosts;
use App\Models\Category_child;
use Illuminate\Http\Request;
use Storage;
use Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use messageResp;
    use uplodeimage;


    //  get posts chaked  //
    public function index(Request $request)
    {
       
        $token = $request->token;
        $curDay = $request->day ; 
        if ($token) {
            
            $id = auth('child')->user()->id;
            $dayFromData = Child::where('id' , $id)->first() ;
            $dayDataBase = $dayFromData->day ; 
            if($curDay != $dayDataBase){
                $dayFromData->update([
                    "current"=>$dayFromData->minute,
                    "day"    =>$curDay
                ]);
            }
            else{
                if($dayFromData->current <= 0){
                    return response() ->json([
                        "status" => "false" , 
                        "msg" => "your allowed time is expired"
                    ]);
                }
            }
            $category = Category_child::select('category_id')->where('child_id', $id)->pluck('category_id');
        } else {
            return $this->error('user not found');
        }


        $posts = Post::latest()->take(10)->whereIn('category_id', $category)->where('status', 1)->get();
        
        if ($posts->count() > 0) {

            foreach ($posts as $post) {

                $post->category_name = $post->belongtocategory->name;
                $post->writer_name = $post->hasWriter->name;
              
                if ($post->has_attachment->count() > 0) {
                    
                    // $post->attachment= "D:/xampp/htdocs/Socail_media3/public/Attachment/1689584136.jpg";
                    $post->attachment = "http://127.0.0.1:8000/Attachment/" .$post->id."/". $post->has_attachment[0]->name;
                    
                                }
                if ($post->hasRecord) {
                   
                    $post->numberofcommetns = $post->hasRecord->where('comments', '!=', null)->count('comments');
                    
                    $post->numberoLovefreaction = $post->hasRecord->where('reaction', '!=', null)->where('reaction', 'love')->count('reaction');
                    
                    
                    $post->numberoLikereaction = $post->hasRecord->where('reaction', '!=', null)->where('reaction', 'like')->count('reaction');
                    
                    
                    $post->numberoDeslikereaction = $post->hasRecord->where('reaction', '!=', null)->where('reaction', 'deslike')->count('reaction');
                    
                    
                    $post->interactiv = $post->hasRecord->where('child_id', $id);}
                
                
            }
            return response()->json([
                "status" => "true",
                "key" => "cheked postes",
                "value" => $posts,


            ]);
        } else {
            return $this->error('posts not found');
        }
    }

    // insert post into database  //
    public function store(Request $request)
    {   ####### validation ######

        $validator = Validator::make($request->all(), [
            'caption'           => 'required|max:100',
            'category_id'       => 'required',
            'token'           => 'required',

        ]);
        $errors = $validator->errors();

        if ($errors->count() > 0) {
            return $this->error($errors->first());
        } else {
            #### store ###########
            $token=$request->token;

            $id = auth('api')->user()->id;
            

            $user = User::find($id);
            if ($user) {
                $role_id = $user->role_id;

                if ($role_id == 1) {
                    $post = Post::create([
                        "caption" => $request->caption,
                        'status' => '0',
                        'category_id' => $request->category_id,
                        'user_id'     => $id

                    ]);

                    ####### move attachment #######
                    $file = $request->file('attachment');

                    // foreach($files as $file )
                    //   {  
                    $exd = $file->getClientOriginalExtension();

                    $name = time() . '.' . $exd;
    
                    //  $imagename=$imagename.$name.',';
                    $file->move(public_path('/Attachment' . '/' . $post->id), $name);
                    //   }
                    //   $imagdb=$imagename;

                    ###### Add name only to database ####1
                    Attach::create([
                        'name'         => $name,
                        'post_id'      => $post->id
                    ]);
                    return $this->sucsess('تم الاضافة بنجاح ');
                } else {
                    return $this->error('ليس لديك صلاحيات لاضافة منشورات');
                }
            } else {
                return $this->error('user not found');
            }
        }
    }


    public function update(Request $request)

    {
        $file = $request->attachments;
        $id = $request->id;

        $post = Post::find($id);

        if ($post) {

            $post->update($request->all());

            if($file){
                $exd = $file->getClientOriginalExtension();
                $name = time() . '.' . $exd;
                $file->move(public_path('/Attachment' . '/' . $post->id), $name);
                $att = Attach::where("post_id", $id);
                $att->update(["name" => $name]);
            }
            return $this->sucsess('تم التعديل بنجاح');
        } else {
            return $this->error('المنشور غير موجود');
        }
    }

    // delete post from database 
    public function destroy(Request $request)
    {

        $id = $request->id;

        $post = Post::find($id);
        $attach = Attach::where('post_id', $id)->first();
        // return $attach;

        if ($post) {
            if ($attach) {
                Storage::disk('public_uploads')->deleteDirectory($attach->post_id);
                $attach->delete();
            }

            $post->delete();

            return $this->sucsess('تم الحذف بنجاح');
        } else {
            return $this->error('المنشور غير موجود');
        }
    }
    //select post meet the category // 
    public function get_postes_for_category(Request $request)
    {

        $category_id = $request->id;

        $posts = Post::where('category_id', $category_id)->get();

        if ($posts->count() > 0) {

            return $this->data_res('posts', $posts);
        } else {
            return $this->error('posts not found');
        }
    }
    // select post unchacked // 
    public function getpostespin()
    {
        $posts = Post::where('status', 0)->get();

        if ($posts->count() > 0) {
            foreach ($posts as $post) {
                if ($post->has_attachment->count() > 0) {


                    $post->attachment = "http://127.0.0.1:8000/Attachment/" . $post->id . '/' . $post->has_attachment[0]->name;
                }
                $post->category_name = $post->belongtocategory->name;
                $post->writer_name = $post->hasWriter->name;
            }
            return $this->data_res('posts_pin', $posts);
        } else {
            return $this->error('posts not found');
        }
    }

    public function getattachment(Request $request)
    {$token=$request->token;

            $id = auth('api')->user()->id;

        $post = Post::find($request->id);
        $attach = $post->has_attachment;
        return $this->data_res('attach', $attach);
    }

    // trans pin post to chaked //
    public function accept(Request $request)
    {
        $post = Post::find($request->id);
        if ($post) {
            $post->update(['status' => 1]);
            return $this->sucsess("تم القبول ");
        } else {
            return $this->error('posts not found');
        }
    }
    //select category available // 
    public function get_category()
    {
        $category = Category::all();
        if ($category->count() > 0) {
            return $this->data_res('category', $category);
        } else {
            return $this->error('لايوجد تصنيفات');
        }
    }


    public function addComment(Request $request)
    {
        $rrpost = null;
        $id = $request->post_id;
        $post = Post::find($id);
        $rpost = RecordsPosts::where('comments', '=', null)->where('child_id', $request->child_id)->where('post_id', $request->post_id)->get();
        if ($rpost->count() > 0) {
            $rrpost = RecordsPosts::find($rpost[0]->id);
            $rrpost->update(['comments' => $request->comment]);
            return $this->sucsess("تم اضافة التعليق ");
        } else {


            if ($post) {
                RecordsPosts::create([
                    "post_id" => $id,
                    "comments" => $request->comment,
                    "child_id" => $request->child_id

                ]);
                return $this->sucsess("تم اضافة التعليق ");
            } else {
                return $this->error(' no post ');
            }
        }
    }

    public function getComment($post_id)

    {
        $post = Post::find($post_id);
        $comments = RecordsPosts::select('comments')->where('comments', '!=', 'null')->where('post_id', $post_id)->get();
        // return $comments;
        if($comments->count()>0){
        $child_id = $post->hasRecord[0]->child_id;
        // return $child_id;
        $child = Child::where('id', $child_id)->get();
        foreach ($child as $c) {
            foreach ($comments as $comment)
                $comment->child = $c->name;
        }
    }
        else{
            return $this->error(' no comments ');

    }



        return $this->data_res('comments', $comments);
    }

    public function addreaction(Request $request)
    {

        $id = $request->post_id;
        $post = Post::find($id);
        $rpost = RecordsPosts::where('reaction', '=', null)->where('child_id', $request->child_id)->where('post_id', $request->post_id)->get();
        if ($rpost->count() > 0) {
            $rrpost = RecordsPosts::find($rpost[0]->id);
            $rrpost->update(['reaction' => $request->reaction]);
            return $this->sucsess("تم اضافة التفاعل ");
        } else {


            if ($post) {
                RecordsPosts::create([
                    "post_id" => $id,
                    "reaction" => $request->reaction,
                    "child_id" => $request->child_id

                ]);
                return $this->sucsess("تم التفاعل  ");
            } else {
                return $this->error(' no post ');
            }
        }
    }


    public function get_reaction()
    {
        $reactoion = ['like', 'love', 'deslike'];
        return response()->json(['reaction' => $reactoion]);
    }
    public function getPostForWriter(Request $request)
    {$token=$request->token;

        $id = auth('api')->user()->id;




        $user_id = $id;
        $posts = Post::where('user_id', $user_id)->get();
        foreach($posts as $post){
        if ($post->has_attachment->count() > 0) {
                    
            // $post->attachment= "D:/xampp/htdocs/Socail_media3/public/Attachment/1689584136.jpg";
            $post->attachment = "http://127.0.0.1:8000/Attachment/" .$post->id."/". $post->has_attachment[0]->name;
        }

    }
        return $posts;
    }

    // add post to record post     //
    public function postShow(Request $request)
    {
        $post_id = $request->post_id;
        $child_id = $request->child_id;
        $post = Post::where('id', $post_id)->get();
        if ($post->count() > 0) {
            RecordsPosts::create([
                "child_id" => $child_id,
                "post_id" => $post_id
            ]);
            return $this->sucsess("تم اضافة المنشور الى قائمة المشاهدات   ");
        } else {
            return $this->error(' المنشور لامكن اضافته اي قاءمة المشاهدة ');
        }
    }
    public function currentTime (Request $request){
        $ChildId = $request->id ; 
        $curDay = $request->day ; 
        $childCurrentTime = $request->current ; 
        $dayFromData = Child::where('id' , $ChildId)->first() ;
        $dayDataBase = $dayFromData->day ;
        if($curDay != $dayDataBase){
            $dayFromData->update([
                "current"=>$dayFromData->minute,
                "day"    =>$curDay
            ]);
            return response() ->json([
                'status' => "true" , 
                "msg" => "the day have been changed" 
            ]) ; 
        }
        else{
            $dayFromData->update([
                "current" => $childCurrentTime 
            ]) ;
            return response() ->json([
                'status' => "true" , 
                "msg" => "the  have been changed" 
            ]) ; 
            
        }
    }
}

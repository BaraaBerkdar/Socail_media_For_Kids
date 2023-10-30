<?php

namespace App\Http\Controllers;

// use App\Models\CategoryChild;
use App\Models\Category_child;

use App\Models\Child;
use App\Models\Post;
use App\Models\User;
use App\Models\RecordsPosts;
use App\Models\Time;
use Illuminate\Http\Request;
// add validator
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Internal\ReturnTypeContract;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;

class ChildController extends Controller
{
    // get all data from child 
    public function index()
    {
    }
    public function show($id)
    {
        $user = User::where('id', $id)
            ->where('role_id', 0)
            ->get();
        if ($user->count() > 0) {
            $childs = Child::where('user_id', $id)
                ->get();
            return response()->json($childs);
        } else {
            return response()->json(
                [
                    'error' => 'Given Wrong Parent id',
                    'key' => '404',
                ],
                404
            );
        }
    }

    public function updateChild(Request $request)
    {
        $id = $request->id;
        $data = Child::where('id', $id)->first();
        if ($data->count() < 1) {
            return response()->json(
                [
                    'error' => 'Given Wrong Child id',
                    'key' => '404',
                ],
                404
            );
        }



        // first let's update time 
        $totalMinutes = $request->input('minute');
        $totalHour = $request->input('hour');
        $totalHour *= 60;
        $totalTime = $totalHour + $totalMinutes;


        // echo $totalTime ; 
        $image = $request->file('profile');
        $newName = "empty";
        if ($request->hasFile('profile')) {
            $newName = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/images'), $newName);
            $newName = "/uploads/images/" . $newName;
        }




        if ($newName == "empty") {
            $data->update([
                'minute' => $totalTime,
            ]);
        } else {
            $data->update([
                'minute' => $totalTime,
                'profile' => $newName
            ]);
        }


        Category_child::where('child_id', $id)->delete();

        $childId = $id;
        $arr = ($request->input('cate_arr'));
        // $arr = "7,".$arr ; 
        // return var_dump($arr);
        $arr1 = explode(",", $arr);
        foreach ($arr1 as $ele) {
            // echo $ele . "  " ; 
            $data = Category_child::create([
                'category_id' => $ele,
                'child_id' => $childId
            ]);
        }
        // return $arr1 ; 

        return response()->json(
            [
                "status" => "true",
                'msg' => "DATA INSERTED SUCCESFULY",
                'key' => '200',
            ],
            200
        );
    }


    // for test
    public function showChild(Request $request)
    {

        // echo "hello" ; 
        $token = $request->token;
        if ($token) {
            $id = auth('api')->user()->id;
            $data = User::where('id', $id)->first();
            $fatherID = $data->id;
            $ChildData = Child::where('user_id', $fatherID)->get();
            // $photoData = Child::where('user_id' , $fatherID)->select('profile')->get() ;

            // if($ChildData->count() < 1){
            //     return response()->json(
            //         [
            //             'msg' => 'Given Wrong Child id',
            //             'status' => 'false' , 
            //             'key' => '404',
            //         ],
            //         404
            //     );
            // } 
            // foreach($ChildData as $miniChild){
            //     $ChildData->profile = "D:/xampp/htdocs/Socail_media3" . $photoData[0]->profile ; 
            // }
            $pathImg = "http://127.0.0.1:8000";
            foreach ($ChildData as $miniChild) {
                $miniChild->profile = $pathImg . $miniChild->profile;
            }
            return response()->json([
                "status" => "true",
                "key" => "cheked child",
                "value" => $ChildData,
                // "profile" => $pathImg .[->profile, 
                "user_id" => $fatherID
            ]);
        }
    }


    public function childProfile(Request $request)
    {
        $token = $request->token;
        $curDay = $request->day;
        if ($token) {
            $id = auth('child')->user()->id;
            // return $id ; 
            $dayFromData = Child::where('id', $id)->first();
            $dayDataBase = $dayFromData->day;
            if ($curDay != $dayDataBase) {
                $dayFromData->update([
                    "current" => $dayFromData->minute,
                    "day"    => $curDay
                ]);
            } else {
                if ($dayFromData->current <= 0) {
                    return response()->json([
                        "status" => "false",
                        "msg" => "your allowed time is expired"
                    ]);
                }
                $ChildData = Child::where('id', $id)->first();
                if ($ChildData->count() < 1) {
                    return response()->json(
                        [
                            'msg' => 'Given Wrong TOKEN',
                            'status' => 'false',
                            'key' => '404',
                        ],
                        404
                    );
                }
                $pathImg = "http://127.0.0.1:8000";
                $ChildData->profile = $pathImg . $ChildData->profile;
                $cat = Category_child::where('child_id', $id)->select('category_id')->get();
                // return $cat ; 
                $retData = [];
                foreach ($cat as $d) {
                    $val = $d['category_id'];
                    array_push($retData, $val);
                }
                sort($retData);
                $ret = Category::whereIn('id', $retData)->select('name', 'id')->get();
                return response()->json([
                    "status" => "true",
                    "key" => "cheked child",
                    "value" => $ChildData,
                    "category" => $ret
                ]);
            }
        }
    }
    public function showCategory(Request $request)
    {
        $id = $request->id;
        $data = Category_child::where('child_id', $id)->select('category_id')->get();
        $retData = [];
        foreach ($data as $d) {
            $val = $d['category_id'];
            array_push($retData, $val);
        }
        sort($retData);
        $ret = Category::whereIn('id', $retData)->select('name', 'id')->get();
        return response()->json([
            "status" => "true",
            "key" => "cheked Category",
            "value" => $ret
        ]);
    }
    // work 
    public function showComments(Request $request)
    {
        $id = $request->id;
        $data = RecordsPosts::where('post_id', $id)->select('comments', 'post_id')->get();
        if ($data->count() < 1) {
            return response()->json(
                [
                    'msg' => 'Given Wrong Post id',
                    "status" => "NO COMMENT ON THIS POST",
                    'key' => '404',
                ],
                404
            );
        }
        return response()->json([
            "status" => "true",
            "key" => "cheked Comment",
            "value" => $data
        ]);
    }


    // work 
    public function showChildPosts(Request $request)
    {
        $id = $request->input('id');
        $data = RecordsPosts::where('child_id', $id)->select('post_id')->get();
        $arrID = [];
        array_pop($arrID);
        foreach ($data as $d) {
            $postId = $d['post_id'];
            array_push($arrID, $postId);
        }
        // print_r($arrID) ;
        $ret = Post::whereIn('id', $arrID)->get();
        // return $ret ; 
        foreach ($ret as $r) {
            // echo $r ; 
            if ($r->has_attachment->count() > 0) {
                $r->attachment = "http://127.0.0.1:8000/Attachment/" . $r['id'] . "/" . $r->has_attachment[0]->name;
            }
        }
        return response()->json([
            "status" => "true",
            "key" => "cheked Category",
            "value" => $ret
        ]);
    }



    // work 
    public function store(Request $request)
    {

        //validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|alpha:ascii',
            'email' => 'required|unique:childs,email',
            'password' => 'required',
            'gender' => 'required',
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'msg' => $validator->errors(),
                "status" => "false",
            ], 400);
        }
        $totalMinutes = $request->input('minute');
        $totalHour = $request->input('hour');
        $totalHour *= 60;
        $totalTime = $totalHour + $totalMinutes;
        $image = $request->file('profile');
        $newName = "empty";
        if ($request->hasFile('profile')) {
            $newName = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/images'), $newName);
            $newName = "/uploads/images/" . $newName;
        }
        $pass = bcrypt($request->input('password'));
        // return response()->json($newName) ; 

        $child = Child::create([
            'name' => $request->input('name'),
            'profile' => $newName,
            'minute' => $totalTime,
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'password' => $pass,
            'user_id' => $request->input('user_id')
        ]);

        $childId = $child->id;
        // $arr = ($request->input('cate_arr'));
        $arr = Category::select('id')->get();
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
    }
}

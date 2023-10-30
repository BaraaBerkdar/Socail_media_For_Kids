<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Trait\messageResp;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Child;

use Illuminate\Support\Facades\Validator;
use Auth;

class AuthController extends Controller
{
    use messageResp;
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $errors = $validator->errors();
        if ($errors->count() > 0) {
            return $this->error($errors->first());
        }

        $caradion = $request->only(['email', 'password']);
        $user_token   = Auth::guard('api')->attempt($caradion);
        $child_token  = Auth::guard('child')->attempt($caradion);

        if ($caradion) {
            if ($user_token) {
                $user = Auth::guard('api')->user();
                $user->token = $user_token;
                switch ($user->role_id) {
                    case 0:
                        return $this->data_res('parent', $user);
                    case 1:
                        return $this->data_res('writer', $user);
                    case 2:
                        return $this->data_res('chacker', $user);
                    case 3:
                        return $this->data_res('admin', $user);
                }
            } elseif ($child_token) {
                $child = Auth::guard('child')->user();
                $childid = Auth::guard('child')->user()->id;
                $curDay = $request->input('day');
                // if($curDay){
                //     $child1=Child::find($childid);
                //     $child1->update(["day"=>$curDay]);
                // }
                $child->token = $child_token;
                return $this->data_res('child', $child);
            } else {
                return response()->json([
                    "status" => "false",
                    "msg" => "INCORECT PASSWORD OR EMAIL"
                ]);
            }
        }
    }



    public function logout(Request $request)
    {
        $token = $request->token;
        if ($token) {
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return  $this->error('some thing went wrongs');
            }
            return $this->sucsess('Logged out successfully');
        } else {
            $this->error('some thing went wrongs');
        }
    }

    public function userinfo(Request $request)
    {

        $token = $request->token;
        if ($token) {
            $user = auth('api')->user();
            $child = auth('child')->user();
            if ($user) {
                return $this->data_res('user', $user);
            } else if ($child) {
                $child = auth('child')->user();
                $role_id = $child->role_id;
                return $this->data_res('child', $child);
            }
        } else {
            return $this->error('the user not found');
        }
    }

    public function register_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|between:2,100',
            'email'           => 'required|string|email|max:100|unique:users',
            'password'        => 'required|string|min:6',
            'phone_number'    => 'required|max:10|min:10|',
            "ssn"             => 'required|max:11|min:11',
            'gender'          => 'required',

        ]);

        $errors = $validator->errors();
        if ($errors->count() > 0) {
            return $this->error($errors->first());
        } else {
            $user = User::create(
                [
                    "name" => $request->name,
                    "email" => $request->email,
                    "password" => bcrypt($request->password),
                    'phone_number' => $request->phone_number,
                    'ssn'        => $request->ssn,
                    'gender'   => $request->gender,
                    "role_id" => 0


                ]
            );
            $token = Auth::guard('api')->attempt(['email' => $request->email, 'password' => $request->password]);
            $user->token = $token;

            return $this->data_res('user', $user);
        }
    }

    public function register_child(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|between:2,100',
            'email'           => 'required|string|email|max:100|unique:users',
            'password'        => 'required|string|min:6',
            'gender'          => 'required',
            "user_id"             => 'required',
        ]);

        $errors = $validator->errors();
        if ($errors->count() > 0) {
            return $this->error($errors->first());
        } else {
            $child = Child::create(
                [
                    "name" => $request->name,
                    "email" => $request->email,
                    "password" => bcrypt($request->password),
                    'user_id' => $request->user_id,
                    'gender'   => $request->gender,


                ]
            );
            $token = Auth::guard('child')->attempt(['email' => $request->email, 'password' => $request->password]);
            $child->token = $token;
            return $this->data_res('child', $child);
        }
    }
    public function check(Request $request)
    {
        $token = $request->token;
        if ($token) {
            $dataAuth = auth('api')->user();
            // $id = auth('api')->user()->id;
            // return $id ; 
            if ($dataAuth) {
                if ($dataAuth->role_id == 0) {
                    return response()->json([
                        "status" => "true",
                        "key" => "parent"
                    ]);
                } else if ($dataAuth->role_id == 1) {
                    return response()->json([
                        "status" => "true",
                        "key" => "writer"
                    ]);
                } else if ($dataAuth->role_id == 2) {
                    return response()->json([
                        "status" => "true",
                        "key" => "check"
                    ]);
                } else {
                    return response()->json([
                        "status" => "true",
                        "key" => "admin"
                    ]);
                }
            } else {
                $dataAuthChild = auth('child')->user();
                if ($dataAuthChild) {
                    return response()->json([
                        "status" => "true",
                        "key" => "child"
                    ]);
                } else {
                    return response()->json([
                        "status" => "false",
                        "key" => "invalid token"
                    ]);
                }
            }
        } else {
            return response()->json([
                "status" => "false",
                "key" => "invalid token"
            ]);
        }
    }
}

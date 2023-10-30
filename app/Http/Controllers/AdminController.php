<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use  App\Trait\messageResp;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{use messageResp;

    public function addParent(Request $request){
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|between:2,100',
            'email'           => 'required|string|email|max:100|unique:users',
            'password'        => 'required|string|min:6',
            'phone_number'    =>'required|max:10|min:10',
            "ssn"             =>'required|max:11|min:11',
            'gender'          =>'required'
        ]);
        $errors = $validator->errors();
        if($errors->count() >0){
            return $this->error($errors->first());
        }
        else{
            $user = User::create(
                [  "name"=>$request->name,
                    "email"=>$request->email,
                    "password"=> bcrypt($request->password),
                    'phone_number'=>$request->phone_number,
                    'ssn'        =>$request->ssn,
                    'gender'   =>$request->gender,


                ]
            );
            return $this->data_res('parent',$user);
    


            }
    }

    public function add_writer(Request $request){

        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|between:2,100',
            'email'           => 'required|string|email|max:100|unique:users',
            'password'        => 'required|string|min:6',
        ]);
        $errors = $validator->errors();
        if($errors->count() >0){
            return $this->error($errors->first());
        }else{

            $user = User::create(
                [  "name"=>$request->name,
                    "email"=>$request->email,
                    "password"=> bcrypt($request->password),
                    "role_id"=>1


                ]
            );
            return $this->data_res('writer',$user,"تم الاضافة بنجاح");


        }
    }

    public function add_checker(Request $request){
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|between:2,100',
            'email'           => 'required|string|email|max:100|unique:users',
            'password'        => 'required|string|min:6',
        ]);
        $errors = $validator->errors();
        if($errors->count() >0){
            return $this->error($errors->first());
        }else{

            $user = User::create(
                [  "name"=>$request->name,
                    "email"=>$request->email,
                    "password"=> bcrypt($request->password),
                    "role_id"=>2


                ]
            );
            return $this->data_res('checker',$user,"تم الاضافة بنجاح");

            }
        }

        public function get_all_parent(){
            $user=User::where('role_id',0)->get();
            if($user->count()>0){
            return $this->data_res('parent',$user);
            }else{

            return $this->error("  لايوجد أباء  ");


            }
        }
            public function get_all_writer(){

                $user=User::where('role_id',1)->get();
                if($user->count()>0){
                return $this->data_res('writer',$user);
                }else{
    
                return $this->error("  لايوجد صانعي محتوى  ");
    
    
                
            }
        }

        public function get_all_checker(){


            $user=User::where('role_id',2)->get();
            if($user->count()>0){
            return $this->data_res('checker',$user);
            }else{

            return $this->error("  لايوجد  مدققين   ");


            
             }
        }
        



}
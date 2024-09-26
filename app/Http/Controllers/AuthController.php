<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;
use Illuminate\Validation\Rule;
use JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'name' => ['required','max:120','string','min:2',],
            'password'=>['required','min:6','confirmed'],
            'username'=>['required','min:6','max:25','unique:users,username'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if(auth()->check() and auth()->user()->user_type_id==3){
            $user = User::create([
                'name'=>$request->name,
                'password'=>$request->password,
                'username'=>$request->username,
                'user_type_id'=>1,
                'active'=>1
            ]);
            return response()->json([
                'message'=>'admin successfully registered',
                'user'=>$user
            ],200);
        }
        $user = User::create([
            'name'=>$request->name,
            'password'=>$request->password,
            'username'=>$request->username,
            'user_type_id'=>2,
        ]);

        return response()->json([
            'message'=>'user successfully registered',
            'user'=>$user
        ],200);
    }
    public function login(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'username'=>'required',
            'password'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if(!$token=Auth::claims(['token_valid'=>true])->attempt(['username' => $request->username, 'password' => $request->password,'active'=>1])){
            return response()->json('The entered data is incorrect or the account has not been activated by the admin.', 401);
        }
        $User=User::where('username','=',$request->username)->first();
        $User->update([
            'token_valid'=>true
        ]);
        return response()->json([
            'access_token'=>$token,
            'user'=>auth()->user(),
        ]);
    }

    public function Logout()
    {
        $Payload=auth()->getPayload();
        $user=User::find($Payload['sub']);
        $user->update([
            'token_valid'=>false
        ]);
        auth()->Logout();
        return response()->json([
            'message'=>'user logged out'
        ]);
    }
}

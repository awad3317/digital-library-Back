<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;



class SuperAdminController extends Controller
{
    public function DeleteAdmin($id)
    {
        $user=User::findOrfail($id);
        if($user->user_type_id==2 or $user->user_type_id==3){
            return response()->json([
                'message'=>'ليس لديك صلاحية لحدف هدا الحساب'
            ], 423);
        }
        $user->delete();
        return response()->json([
            'message'=>'Admin successfully deleted',
            'data'=>$user,
        ], 200);
    }

    public function UpdateAdmin(Request $request,$id)
    {
        $user=User::findOrfail($id);
        $validator=Validator::make($request->all(),[
            'name' => ['required','max:120','string','min:2',],
            'username'=>['required','min:6','max:25',Rule::unique('users')->ignore($id)],
            'user_type_id'=>['required',Rule::exists('user_types', 'id')],
            'active'=>['required','boolean']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if($user->user_type_id==2 or $user->user_type_id==3){
            return response()->json([
                'message'=>'ليس لديك صلاحية لتعديل هدا الحساب'
            ], 423);
        }
        $user->update([
            'name'=>$request->name,
            'username'=>$request->username,
            'user_type_id'=>$request->user_type_id,
            'active'=>$request->active,
            'token_valid'=>false
        ]);
        return response()->json([
            'message'=>'Admin successfully updated',
            'data'=>$user,
        ], 200);
    }
}

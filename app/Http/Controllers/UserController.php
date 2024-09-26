<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Hash;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTExcepton;
use JWTAuth;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // https://example.com?like=users.name,[value] | https://example.com?like=users.username,[value]
    {
        if(auth()->check() and auth()->user()->user_type_id==1){
            $users=User::join('user_types','user_types.id','=','users.user_type_id')->where('user_types.id','=','2')
            ->Select('users.id','users.name','users.username','users.active','user_types.type')
            ->filter()->paginate(PAGINATION_COUNT);
            return response()->json($users, 200);
        }
        elseif(auth()->check() and auth()->user()->user_type_id==3){
            $users=User::join('user_types','user_types.id','=','users.user_type_id')->where('user_types.id','=','1')
            ->Select('users.id','users.name','users.username','users.active','user_types.type')
            ->filter()->paginate(PAGINATION_COUNT);
            return response()->json($users, 200);
        }
        else{
            $users=User::where('user_type_id','=','2')->where('active','=','1')
            ->Select('name')->orderBy('name','ASC')->filter()->inRandomOrder()->get();
            return response()->json($users, 200);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::findOrfail($id);
        $user_type_id=$user->user_type_id;
        $user_type = DB::table('user_types')
        ->select('type')
        ->where('user_types.id', '=', $user_type_id)
        ->get();
        $user =[
            $user,$user_type
        ];
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,$id)
    {
        $id = Crypt::decrypt($id);
        $user=User::findOrfail($id);
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user=auth()->user();
        $validator=Validator::make($request->all(),[
            'name' => ['required','max:120','string','min:2',],
            'username'=>['required','min:6','max:25',Rule::unique('users')->ignore($user->id)],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user->update([
            'name'=>$request->name,
            'username'=>$request->username,
            'token_valid'=>false
        ]);
        return response()->json([
            'message'=>'user successfully updated',
            'data'=>$user,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $user=User::findOrfail($id);
        $user->delete();
        $user->Lecture()->delete();
        return response()->json([
            'message'=>'user successfully deleted',
            'data'=>$user,
        ], 200);
    }
    public function changePassword(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'old_password'=>'required',
            'password'=>'required|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user=auth()->user();
        if(!Hash::check($request->old_password, $user->password)){
            return response()->json([
                'message'=>'the old password is incorrect'
            ], 400);
        }

        $user->update([
            'password'=>$request->password
        ]);
        auth()->Logout();
        return response()->json([
            'message'=>'your password has been changed.. Login again'
        ], 200);
    }
    public function change_Password_by_admin(Request $request,$id)
    {
        $id = Crypt::decrypt($id);
        $this->middleware('CheckAdmin|CheckSuperAdmin');
        $validator=Validator::make($request->all(),[
            'password'=>'required|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user=User::findOrfail($id);
        if(auth()->check() and auth()->user()->user_type_id==3){
            if($user->user_type_id==1){
                $user->update([
                    'password'=>$request->password,
                    'token_valid'=>false
                ]);
                return response()->json([
                    'message'=>'Admin : '. $user->username .' password has been changed'
                ], 200);
            }
            else{
                return response()->json([
                    'message'=>'ليس لديك صلاحية لتغير باسورد هدا المستخدم'
                ], 200);
            }
        }
        if(auth()->check() and auth()->user()->user_type_id==1){
            if($user->user_type_id!=2){
                return response()->json([
                    'message'=>'ليس لديك صلاحية لتغير باسورد هدا المستخدم'
                ], 200);
            }
        }
        $user->update([
            'password'=>$request->password,
            'token_valid'=>false
        ]);
        return response()->json([
            'message'=>'User : '. $user->username .' password has been changed'
        ], 200);
    }

    public function active_user(Request $request,$id)
    {
        $id = Crypt::decrypt($id);
        $user=User::findOrfail($id);
        $validator=Validator::make($request->all(),[
            'active'=>['required','boolean']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user->update([
            'active'=>$request->active
        ]);
        return response()->json([
            'message'=>'the user is actived',
            'data'=>$user
        ], 200);

    }

}

<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    //


    public function Register(Request $request){
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'adress' => ['required', 'string', 'max:200'],
            'role' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required',  Password::min(8)],
        ]);
        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->adress = $request->adress;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'status'=>1,
            'msg'=>"Add User sucess !!!",

        ]);
    }

    public function Login(Request $request){
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required'],
            'password' => ['required',  Password::min(8)],

        ]);
        $user=User::where("email","=",$request->email)->first();

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password,'role'=>$request->role],true)) {
            // create a token
            $token = $user->createToken("Auth_token")->plainTextToken;
            DB::table('users')->where('id',$user->id)->update(['online'=>true]);
            return response()->json([
                'status'=>1,
                'msg'=>"Login Sucess",
                'user'=>$user,
                'token_type'=>'bearer',
                'access_token'=>$token,
                // 'expires_in'=>auth()->factory()->getTTL()*60,
            ]);

        }
        else{
            return response()->json([
                'status'=>0,
                'msg'=>"the Data from frontEnd is not correct",
            ]);
        }
    }
    public function UserProfil(){
        return response()->json([
            'status'=>1,
            'msg'=>"Profil User ",
            'data'=> auth()->user()
        ]);

    }

    public function Logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>1,
            'msg'=>"End session",
        ]);
    }

}

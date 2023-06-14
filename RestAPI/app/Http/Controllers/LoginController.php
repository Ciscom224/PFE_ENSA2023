<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\ValidationException as ValidationValidationException;


class LoginController extends Controller
{
    //
    public function index(){
        $users=User::all();

        return $users;
    }
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'adress' => ['required', 'string', 'max:200'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        ]);

        $user = new User;
        $user->user_first_name = $request->first_name;
        $user->user_last_name = $request->last_name;
        $user->adress = $request->adress;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            "message"=>"Utilisateur bien recu"
        ]);




    }

    public function login(Request $resquest){
        $credentiels=$resquest->validate([
            'email'=>"required",
            'password'=>'required',
        ]);

        if(! Auth::attempt($credentiels)){
            throw ValidationValidationException::withMessages([
                'email'=>[
                    __('auth.failed')
                ]
                ]);

        }
        return $resquest->user();
        // return response()->json([
        //     'user'=>$resquest->user()
        // ]);

    }

    public function logout(){
        return Auth::logout();
    }
}

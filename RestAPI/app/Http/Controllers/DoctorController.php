<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $doctors = DB::table('users')->where('role',"=","Doctor")->get();

        return response()->json([
            'doctors'=>$doctors,
        ]);
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
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required',  Password::min(8)],
        ]);

        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->adress = "default";
        $user->email = $request->email;
        $user->role = "Doctor";
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status'=>1,
            'msg'=>"Add Doctor sucess !!!",

        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $doctor=User::find($id);
        return response()->json([
            'doctor'=>$doctor
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        User::destroy($id);
        return response()->json([
            'message'=>"doctor delete success..."
        ]);

    }
}

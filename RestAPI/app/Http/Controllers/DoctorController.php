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
        if(empty($doctors)){
            return response()->json([
                'doctors'=>[]
            ]);
        }
        else
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
        $user->user_first_name = $request->first_name;
        $user->user_last_name = strtoupper($request->last_name);
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


    public function doctor_patient(string $name){

        if (strcmp($name,"All")!=0)
        {
            $doctor_id=explode("_",$name);
            $id=(int)$doctor_id[1];

            $patients=DB::table('patients')
            ->join('users', 'users.id', '=', 'patients.doctor_id')
            ->where('patients.doctor_id','=',$id)
            ->select('users.user_last_name','users.user_first_name','patients.patient_id','patients.first_name', 'patients.birth_day', 'patients.last_name',)
            ->get();


            return response()->json([
                'patients'=>$patients
            ]);
        }
        else{
            $patients=DB::table('patients')
            ->join('users', 'users.id', '=', 'patients.doctor_id')
            ->select('patients.patient_id','patients.first_name', 'patients.last_name', 'patients.birth_day','users.*',)
            ->get();

            return response()->json([
                'msg'=>$name,
                'patients'=>$patients
            ]);
        }

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

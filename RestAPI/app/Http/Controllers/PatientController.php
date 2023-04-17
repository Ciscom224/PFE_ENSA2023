<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $patients=Patient::all();
        return response()->json([
            'patients'=>$patients,
            'status'=>1
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $patient=new Patient;
        $patient->patient_id=$request->patient_id;
        $patient->first_name=$request->first_name;
        $patient->middle_name=$request->middle_name;
        $patient->last_name=$request->last_name;
        $patient->birth_day=$request->birth_day;
        $patient->adress=$request->adress;
        $patient->city=$request->city;
        $patient->phone_1=$request->phone_1;
        $patient->phone_2=$request->phone_2;
        $patient->gender=$request->gender;
        $patient->blood_group=$request->blood_group;
        $patient->is_allergy=$request->is_allergy;
        $patient->is_chronic=$request->is_chronic;
        $patient->save();
        return response()->json([
            'status'=>1
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $patient=DB::table('patients')->where('patient_id','=',$id)->get();
        return response()->json([
            'patient'=>$patient,
            'status'=>1
        ]);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        DB::table('patients')->where('patient_id','=',$id)->update([
            'first_name'=>$request->first_name,
            'middle_name'=>$request->middle_name,
            'last_name'=>$request->last_name,
            'birth_day'=>$request->birth_day,
            'adress'=>$request->adress,
            'city'=>$request->city,
            'phone_1'=>$request->phone_1,
            'phone_2'=>$request->phone_2,
            'gender'=>$request->gender,
            'blood_group'=>$request->blood_group,
            'is_allergy'=>$request->is_allergy,
            'is_chronic'=>$request->is_chronic,
        ]);
        return response()->json([
            'message'=>"Update patient success ...",
            'status'=>1,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        DB::table('patients')->where('patient_id','=',$id)->delete();
        return response()->json([
            'message'=>"Patient delete success..."
        ]);
    }
}

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

        // if ($request->hasFile('image')) {
        //     $file=$request->file('profil_image');
        //     $fileName=$file->getClientOriginalName();
        //     $finalName=$request->patient_id . $request->first_name;

        //     $request->file('profil_image')->storeAs('patients/',$finalName,'public');
        //     $msg="Bien";
        // }
        // else $msg="non.....";




        $patient=new Patient;
        $patient->patient_id=htmlspecialchars($request->ID);
        $patient->first_name=$request->first_name;
        $patient->middle_name=$request->middle_name;
        $patient->last_name=$request->last_name;
        $patient->email=$request->email;
        $patient->regDate=$request->regDate;
        $patient->birth_day=$request->birth_day;
        $patient->adress=$request->adress;
        $patient->city=$request->city;
        $patient->phone_1=$request->phone_1;
        $patient->phone_2=$request->phone_2;
        $patient->gender=$request->gender;
        $patient->blood_group=$request->blood_group;
        $patient->profil_image='default';
        $patient->is_allergy=$request->is_allergy;
        $patient->is_chronic=$request->is_chronic;
        $patient->save();
        return response()->json([
            'status'=>1,
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //
        if(empty($request->patient_id) & empty($request->first_name) & empty($request->middle_name) &empty($request->last_name) ){
            return response()->json([
                'msg'=>"All is null",
                'patients'=>Patient::all(),
               
            ]);
        }
        elseif (!empty($request->patient_id)) {
            $patients=DB::table('patients')->where('patient_id','=',$request->patient_id)->get();
            if (count($patients)==0) {
                return response()->json([
                    'msg'=>'No Patient ID : ',
                    'patients'=>$patients,
                    'status'=>0
    
                ]);
            } else {
                return response()->json([
                    'msg'=>'first ID is not null',
                    'patients'=>$patients,
    
                ]);
            }
        } elseif(!empty($request->first_name)) {
            $patients=DB::table('patients')->where('first_name','LIKE',$request->first_name)->get();

            if (count($patients)==0) {
                return response()->json([
                    'msg'=>'No Patient ',
                    'status'=>0
    
                ]);
            } else {
                return response()->json([
                    'msg'=>'first ID is not null',
                    'patients'=>$patients,
    
                ]);
            }
            
         
        }
      
        elseif(!empty($request->last_name)) {
            $patients=DB::table('patients')->where('last_name','LIKE',$request->last_name)->get();

              if (count($patients)==0) {
                return response()->json([
                    'msg'=>'No Patient Last Name',
                    'status'=>0
    
                ]);
            } else {
                return response()->json([
                    'msg'=>'Last Name is not null',
                    'patients'=>$patients,
    
                ]);
            }
        }
        elseif(!empty($request->middle_name)) {
            $patients=DB::table('patients')->where('middle_name','LIKE',$request->middle_name)->get();

           
              if (count($patients)==0) {
                return response()->json([
                    'msg'=>'No Patient Middle Name',
                    'status'=>0
    
                ]);
            } else {
                return response()->json([
                    'msg'=>'Middle Name is not null',
                    'patients'=>$patients,
    
                ]);
            }
        }
        else
            return response()->json([
                'patient'=>"rien a dire"
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

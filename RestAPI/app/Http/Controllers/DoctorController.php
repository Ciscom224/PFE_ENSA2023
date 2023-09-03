<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\PatientHasDoctor;
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

        $doctors = User::where("role", "Doctor")->get();
        return response()->json([
            'doctors' => $doctors,
        ]);
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
        ]);
        
        $user = new User;
        $user->user_first_name = htmlspecialchars($request->first_name);
        $user->user_last_name = htmlspecialchars(strtoupper($request->last_name));
        $user->adress = "default";
        $user->email = $request->email;
        $user->role = "Doctor";
        $user->speciality_id = $request->speciality;
        $user->service_id = $request->service;
        $user->password = Hash::make($request->user_last_name."1234");
        $user->save();

        return response()->json([
            'status' => 1,
            'message' => "Ajout Docteur reussit !!!",

        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $doctor = User::find($id);
        return response()->json([
            'doctor' => $doctor
        ]);
    }
    public function searchDoctor($search){

        $search=$search=="none"? "": $search;

        $doctors=User::where('role',"Doctor")
        ->when(!empty($search),function ($query) use($search){
            $query->where('users.email','LIKE','%'.$search.'%')
            ->orWhere('users.user_first_name','LIKE','%'.$search.'%')
            ->orWhere('users.user_last_name','LIKE','%'.$search.'%')
            ->orWhere('users.email','LIKE','%'.$search.'%')
            ->orWhere('services.name','LIKE','%'.$search.'%')
            ->orWhere('specialities.name','LIKE','%'.$search.'%');
        })
        ->join('services', 'services.id', '=', 'users.service_id')
        ->join('specialities', 'specialities.id', '=', 'users.speciality_id')
        ->select('users.id','users.user_first_name as fname','users.user_last_name as lname','users.email','services.name as service','specialities.name as speciality')
        ->orderBy('lname')
        ->get();

        return response()->json([
            'doctors'=>$doctors,
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
    public function update(Request $request,$id)
    {
        //
        $doctor=User::findOrFail($id);
        $doctor->user_first_name = htmlspecialchars($request->fname);
        $doctor->user_last_name = htmlspecialchars(strtoupper($request->lname));
        $doctor->email = htmlspecialchars($request->email);
        $doctor->speciality_id = $request->speciality;
        $doctor->service_id = $request->service;
        $doctor->save();
        return response()->json([
            'status'=>224,
            'message'=>"Docteur mise a jour reussit !!!",
        ]);

    }


    public function doctor_patient(string $name)
    {

        if (strcmp($name, "All") != 0) {
            $doctor_id = explode("_", $name);
            $id = (int)$doctor_id[1];

            $patients = DB::table('patients')
                ->join('users', 'users.id', '=', 'patients.doctor_id')
                ->where('patients.doctor_id', '=', $id)
                ->select('users.user_last_name', 'users.user_first_name', 'patients.id', 'patients.first_name', 'patients.birth_day', 'patients.last_name',)
                ->get();


            return response()->json([
                'patients' => $patients
            ]);
        } else {
            $patients = DB::table('patients')
                ->join('users', 'users.id', '=', 'patients.doctor_id')
                ->select('patients.id', 'patients.first_name', 'patients.last_name', 'patients.birth_day', 'users.*',)
                ->get();

            return response()->json([
                'msg' => $name,
                'patients' => $patients
            ]);
        }
    }

    public function patients_for_this_doctor($doctor_id)
    {
        
        $doctor_id==0?"":$doctor_id;
        $patients = PatientHasDoctor::when(!empty($doctor_id),function($query) use($doctor_id){
            return $query->where('patient_has_doctors.doctor_id', $doctor_id);
        })
        ->join('users', 'users.id', '=', 'patient_has_doctors.doctor_id')
        ->join('patients', 'patients.id', '=', 'patient_has_doctors.patient_id')
        ->select('users.user_first_name as Dfname','users.user_last_name as Dlname','patients.id as patient_id', 'patients.first_name', 'patients.last_name', 'patients.regDate', 'patients.gender', 'patients.birth_day', 'patients.blood_group')
        ->get();

        return response()->json([
            'patients' => $patients
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        User::findOrFail($id)->delete();
        return response()->json([
            'message' => "Docteur supprime !!!"
        ]);
    }

    public function doctorStats()
    {
        $appointmentStat = Appointment::where('patient_has_doctors.doctor_id', auth()->user()->id)
            ->join('patient_has_doctors', 'appointments.patient_id', '=', 'patient_has_doctors.patient_id')
            ->select('appointments.status', DB::raw('COUNT(*) as count'))
            ->groupBy('appointments.status')
            ->get();

        $appointmentStatMonth = Patient::whereHAs('affections',function($query){
            $query->where('patient_has_doctors.doctor_id', auth()->user()->id);
        })
            ->select(DB::raw('MONTH(patients.regDate) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('MONTH(patients.regDate)'))
            ->get()
            ->each(function ($item) {
                $mois = $item->month;
                $nomMois = \Carbon\Carbon::createFromFormat('!m', $mois)->locale('fr')->monthName;
                $item->month = $nomMois;
            });
        return response()->json([
            'patientMonth' => $appointmentStatMonth,
            'appointStat' => $appointmentStat,
        ]);
    }
}

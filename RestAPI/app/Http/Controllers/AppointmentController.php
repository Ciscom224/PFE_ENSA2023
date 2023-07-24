<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $appointments=Appointment::join('patients', 'appointments.patient_id', '=', 'patients.id')
        ->select('patients.id as patient_id','patients.first_name','patients.last_name', 'patients.regDate as enter_d','patients.phone_1 as tel','appointments.id as id','appointments.title','appointments.decription as description','appointments.start','appointments.id as id','appointments.title','appointments.decription as description','appointments.end','appointments.status as color')
        ->get();
        // dd($appointments);
        return response()->json([
            'appointments'=>$appointments
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
        //return response($request->description);
        $startDate = Carbon::parse($request->start)->addHour();
        Appointment::create([
            'title'=>htmlspecialchars($request->title),
            'code'=> "A". random_int(0,100),
            'decription' =>htmlspecialchars($request->description),
            'patient_id'=>htmlspecialchars($request->patient_id),
            "start"=>$startDate->toDateTimeString(),
            "status"=>"#fff"
        ]);
        return response()->json([
            'msg'=>"Add Appontment  sucess !!!"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function edit(Request $request)
    {
        //
        $appointment=Appointment::findOrFail($request->id);
        $appointment->title=$request->title;
        $appointment->decription=$request->description;
        $appointment->status=$request->status;
        $appointment->save();

        return response()->json([
            'msg'=>"Appointment Update sucess !!!"
        ]);
    }

    public function patientAppointment($patient_id){

        $appointments=Appointment::where('patient_id',$patient_id)->orderBy('Date_ap')->get();
        return response()->json([
            'appointments'=>$appointments
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $appointment=Appointment::findOrFail($request->id);
        $appointment->start=Carbon::parse($request->start)->addHour()->toDateTimeString();
        $appointment->end=Carbon::parse($request->end)->addHour()->toDateTimeString();
        $appointment->save();
        return response()->json([
            'msg'=>"Update Appontment time sucess !!!"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $appointment=Appointment::findOrFail($id);
        $appointment->delete();
        return response()->json([
            'msg'=>"Appointment Deleted Sucess !!"
        ]);
    }
}

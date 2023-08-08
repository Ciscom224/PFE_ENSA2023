<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        //

        $appointments = Patient::join('appointments', 'appointments.patient_id', '=', 'patients.id')
            ->join('patient_has_doctors', function ($query) {
                $query->where('patient_has_doctors.doctor_id', auth()->user()->id);
            })
            ->select('patients.id as patient_id', 'patients.first_name', 'patients.last_name', 'patients.regDate as enter_d', 'patients.phone as tel', 'appointments.id as id', 'appointments.title', 'appointments.decription as description', 'appointments.start',  'appointments.end', 'appointments.status as color')
            ->distinct("patients.id")
            ->get();
        return response()->json([
            'appointments' => $appointments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function search(Request $request)
    {
        //
        $patients = Patient::where('doctor_id', $request->doctor_id)
            ->when(!empty($request->searchString), function ($query) use ($request) {
                $query->where('id', 'LIKE', '%' . $request->searchString . '%')
                    ->orWhere('first_name', 'LIKE', '%' . $request->searchString . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $request->searchString . '%')
                    ->orWhere('blood_group', 'LIKE', '%' . strtoupper($request->searchString) . '%')
                    ->orWhere('adress', 'LIKE', '%' . $request->searchString . '%');
            })
            ->when(!empty($request->searchDate), function ($query) use ($request) {
                $query->where('regDate', '=', $request->searchDate);
            })
            ->select('patients.id as patient_id', 'patients.first_name', 'patients.last_name', 'patients.regDate', 'patients.gender')

            ->get();

        $appointments = Appointment::join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->where('patients.doctor_id', $request->doctor_id)
            ->when(!empty($request->searchString), function ($query) use ($request) {
                $query->where('patients.id', 'LIKE', '%' . $request->searchString . '%')
                    ->orWhere('patients.first_name', 'LIKE', '%' . $request->searchString . '%')
                    ->orWhere('patients.last_name', 'LIKE', '%' . $request->searchString . '%')
                    ->orWhere('patients.blood_group', 'LIKE', '%' . strtoupper($request->searchString) . '%')
                    ->orWhere('patients.adress', 'LIKE', '%' . $request->searchString . '%');
            })
            ->when(!empty($request->searchDate), function ($query) use ($request) {
                $query->where('patients.regDate', '=', $request->searchDate);
            })
            ->select('patients.id as patient_id', 'patients.first_name', 'patients.last_name', 'patients.regDate as enter_d', 'patients.phone as tel', 'appointments.id as id', 'appointments.title', 'appointments.decription as description', 'appointments.start', 'appointments.end', 'appointments.status as color')
            ->get();
        //    dd($patients);
        return response()->json([
            'patients' => $patients,
            'appoitments' => $appointments
        ]);
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
            'title' => htmlspecialchars($request->title),
            'code' => "A" . random_int(0, 100),
            'decription' => htmlspecialchars($request->description),
            'patient_id' => htmlspecialchars($request->patient_id),
            'doctor_id' => auth()->user()->id,
            "start" => $startDate->toDateTimeString(),
            "end" => Carbon::parse($request->start)->addHour(2)->toDateTimeString(),
            "status" => "#ffc107"
        ]);
        return response()->json([
            'msg' => "Add Appontment  sucess !!!"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function edit(Request $request)
    {
        //
        $appointment = Appointment::findOrFail($request->id);
        $appointment->title = $request->title;
        $appointment->decription = $request->description;
        $appointment->status = $request->status;
        $appointment->save();

        return response()->json([
            'msg' => "Appointment Update sucess !!!"
        ]);
    }

    public function patientAppointment($patient_id, $status)
    {
        // dd($patient_id,$status);
        $status = $status == "none" ? "" : $status;
        $appointments = Appointment::whereHas('patient', function ($query) use ($patient_id) {
            return $query->where('id', $patient_id);
        })
            ->when(!empty($status), function ($query) use ($status) {
                return $query->where('status', "#" . $status);
            })
            ->get();

        // dd($appointments);
        return response()->json([
            'appointments' => $appointments
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $appointment = Appointment::findOrFail($request->id);
        $appointment->start = Carbon::parse($request->start)->addHour()->toDateTimeString();
        $appointment->end = Carbon::parse($request->end)->addHour()->toDateTimeString();
        $appointment->save();
        return response()->json([
            'msg' => "Update Appontment time sucess !!!"
        ]);
    }

    public function cancelAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = "#B0B0B0";
        $appointment->save();

        return response()->json([
            'msg' => "Appointment Cancel sucess !!!"
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return response()->json([
            'msg' => "Appointment Deleted Sucess !!"
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Service;
use App\Models\Speciality;
use Illuminate\Http\Request;
use App\Models\PatientHasDoctor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //

    public function addService(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'max_effectif' => ['required', 'string', 'max:255'],

        ]);
        $service = new Service;
        $service->name = $request->name;
        $service->max_effectif = $request->max_effectif;
        $service->status = false;
        $service->save();


        return response()->json([
            'message' => "Ajout service recu..",
        ]);
    }

    public function getAllService($search)
    {
        $search = $search == "none" ? "" : $search;
        $services = Service::when(!empty($search), function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('max_effectif', 'LIKE', '%' . $search . '%');
        })
            ->get();

        return response()->json([
            'services' => $services
        ]);
    }
    public function changeStatus($id)
    {
        $service = Service::findOrFail($id);
        if ($service->status) {
            $service->status = false;
            $message = "Service Inactif...";
        } else {
            $service->status = true;
            $message = "Service Actif...";
        }
        $service->save();
        return response()->json([
            'message' => $message
        ]);
    }
    public function destroyService($id)
    {
        Service::findOrFail($id)->delete();
        return response()->json([
            'message' => "Suppression du Service recu..."
        ]);
    }
    public function addSpeciality(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],

        ]);
        $speciality = new Speciality;
        $speciality->name =strtoupper(htmlspecialchars($request->name));
        $speciality->save();

        return response()->json([
            'message' => "Ajout de Specialite recu ..."
        ]);
    }

    public function getAllSpeciality()
    {
        $specs = Speciality::orderBy('created_at','desc')
        ->get();

        return response()->json([
            'specialities' => $specs
        ]);
    }

    public function getSpeciality(int $id)
    {
        $specs = DB::table('specialities')->where('speciality_id', '=', $id)->get();
        return response()->json([
            'speciality' => $specs
        ]);
    }

    public function delSpeciality(int $id)
    {
        Speciality::findOrFail($id)->delete();
        return response()->json([
            'message' => "Suppression de la Specialite recue..."
        ]);
    }

    public function doctorToSpeciality(Request $request)
    {


        DB::table('users')
            ->where('id', $request->doctor_id)
            ->update(['speciality_id' => $request->speciality_id]);

        return response()->json([
            'msg' => "affect success..."
        ]);
    }
    public function doctorsSpeciality(string $id)
    {

        $doctors = User::where("speciality_id", $id)->get();
        return response()->json([
            'id'=>$id,
            'doctors' => $doctors
        ]);
    }

    public function doctorToPatient(Request $request)
    {

        $phd = new PatientHasDoctor;
        $phd->patient_id = htmlspecialchars($request->patient_id);
        $phd->doctor_id = htmlspecialchars($request->doctor_id);
        $phd->save();
        return response()->json([
            'status' => 1,
            'message' => "Affectation reussit !!! "

        ]);
    }
    public function Stats($search)
    {
        $search = "";

        $appointments = Appointment::when(!empty($search), function ($query) use ($search) {
            $query->whereDate('appointments.start', $search);
        })
            ->join('patients', 'patients.id', '=', 'appointments.patient_id')
            ->join('users', 'users.id', '=', 'appointments.doctor_id')
            ->select('patients.id as patient_id', 'patients.regDate as date_entre', DB::raw("DATE_FORMAT(appointments.start, '%d/%m/%Y') AS date"), 'appointments.title', 'appointments.status', 'users.user_last_name as doctor')
            ->orderBy('date', 'desc')
            ->get();
        $statAppoints = Appointment::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
        $statAppointsDay = Appointment::whereDate('start', Carbon::now())
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
        return response()->json([
            'appointments' => $appointments,
            'totalPatient' => Patient::count(),
            'totalconcultation' => Appointment::count(),
            'totalDoctor' => User::where('role', "Doctor")->count(),
            'statAppoints' => $statAppoints,
            'statAppointsDay' => $statAppointsDay,
        ]);
    }

    public function usersOnline(){
        $users=User::where('online',1)->limit(4)->get();

        return response()->json([
            'status'=>224,
            'users'=>$users,
        ]);
    }
}

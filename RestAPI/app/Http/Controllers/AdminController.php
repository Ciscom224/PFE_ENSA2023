<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use App\Models\Doctor_spe;
use App\Models\Speciality;
use Illuminate\Http\Request;
use App\Models\Doctor_patient;

use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Promise\all;

class AdminController extends Controller
{
    //

    public function addService(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'max_effectif' => ['required', 'string', 'max:255'],

        ]);
        $service = new Service;
        $service->name=$request->name;
        $service->max_effectif=$request->max_effectif;
        $service->save();


        return response()->json([
            'message'=>"Add service success.."
        ]);



    }

    public function getAllService(){
        $services=Service::all();

        return response()->json([
            'services'=>$services
        ]);
    }
    public function addSpeciality(Request $request){
        DB::table('specialities')
        ->updateOrInsert(
            ['name' => strtoupper($request->name)],
            ['name' => strtoupper($request->name)]
        );

        return response()->json([
            'msg'=>"Add spe success ..."
        ]);
    }

    public function getAllSpeciality(){
        $specs=Speciality::all();

        return response()->json([
            'specialities'=>$specs
        ]);
    }

    public function getSpeciality(int $id){
        $specs=DB::table('specialities')->where('speciality_id','=',$id)->get();
        return response()->json([
            'speciality'=>$specs
        ]);
    }

    public function delSpeciality(int $id){
        $specs=DB::table('specialities')->where('speciality_id','=',$id)->delete();
        return response()->json([
            'speciality'=>$specs,
            'msg'=>"delete success..."
        ]);
    }

    public function doctorToSpeciality(Request $request){


        DB::table('users')
        ->where('id', $request->doctor_id)
        ->update(['speciality_id' => $request->speciality_id]);

        return response()->json([
            'msg'=>"affect success..."
        ]);
    }
    public function doctorsSpeciality(string $id){

        $doctors=User::where("speciality_id",$id)->get();
        return response()->json([
            'doctors'=>$doctors
        ]);
    }

    public function doctorToPatient(Request $request){

        return response()->json([
            'all'=>$request->all()
        ]);

        return response()->json([
            'status'=>1,
            'msg'=>"succes..."

        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

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
}

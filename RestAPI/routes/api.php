<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\PatientController;



Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
  //====================================================================//
 //                      AUTHENTIFICATION ROUTES                       //
//====================================================================//

Route::post('/login', [UserController::class, 'Login']);
Route::post('/register', [UserController::class, 'Register']);

Route::group(['middleware' => ["auth:sanctum"]], function () {

    Route::get('/user-profil', [UserController::class, 'UserProfil']);
    Route::get('/logout', [UserController::class], 'Logout');


      //====================================================================//
     //                      ADMIN ROUTES                                  //
    //====================================================================//

    Route::post('/addService', [AdminController::class, 'addService']);
    Route::get('/services', [AdminController::class, 'getAllService']);


    Route::post('/addSpeciality', [AdminController::class, 'addSpeciality']);
    Route::get('/specialities', [AdminController::class, 'getAllSpeciality']);
    Route::get('/speciality/{id}', [AdminController::class, 'getSpeciality']);
    Route::delete('/speciality/{id}', [AdminController::class, 'delSpeciality']);

    Route::post('/doctor_to_speciality', [AdminController::class, 'doctorToSpeciality']);
    Route::get('/doctors_speciality/{id}', [AdminController::class, 'doctorsSpeciality']);

    Route::post('/doctor_to_patient', [AdminController::class, 'doctorToPatient']);

      //====================================================================//
     //                      DOCTOR ROUTES                                 //
    //====================================================================//
    Route::post('/addDoctor', [DoctorController::class, 'store']);
    Route::get('/doctors', [DoctorController::class, 'index']);
    Route::get('/doctor/{id}', [DoctorController::class, 'show']);
    Route::delete('/doctor/{id}', [DoctorController::class, 'destroy']);


      //====================================================================//
     //                     PATIENT ROUTES                                 //
    //====================================================================//
    Route::post('/addPatient', [PatientController::class, 'store']);
    Route::post('/getPatient', [PatientController::class, 'show']);
    Route::get('/allPatient', [PatientController::class, 'index']);
    Route::post('/updatePatient/{id}', [PatientController::class, 'update']);
    Route::delete('/delPatient/{id}', [PatientController::class, 'destroy']);

});

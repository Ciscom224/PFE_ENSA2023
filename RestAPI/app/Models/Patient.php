<?php

namespace App\Models;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory,SoftDeletes;
    protected $primaryKey = 'id';

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function affections()
    {
        return $this->hasMany(PatientHasDoctor::class,'patient_id');
    }
    public function demande()
    {
        return $this->hasMany(PatientHasDemande::class,'patient_id');
    }
    public function users(){
        return $this->hasMany(User::class);

    }

}

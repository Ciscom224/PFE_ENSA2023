<?php

namespace App\Models;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientHasDoctor extends Model
{
    use HasFactory;
    protected $fillable = [
        'doctor_id',
        'patient_id',
    ];

    public function patients(){
        return $this->hasMany(Patient::class,"id");
    }
}

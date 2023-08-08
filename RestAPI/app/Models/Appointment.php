<?php

namespace App\Models;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory,SoftDeletes;
    public $timestamps = false;
    // protected $dateFormat = 'timestamp';
    protected $fillable = ['title','patient_id','doctor_id','code','decription','start','end','status'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

<?php

namespace App\Models;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $dateFormat = 'timestamp';
    protected $fillable = ['title','patient_id','code','decription','start','end','status'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

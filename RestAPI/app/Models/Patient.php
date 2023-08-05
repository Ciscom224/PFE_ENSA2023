<?php

namespace App\Models;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function users(){
        return $this->hasMany(User::class);

    }
}

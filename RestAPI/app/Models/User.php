<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table='users';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    public $timestamps=false;

    protected $hidden = ['password', 'remember_token',];

    public function Speciality(): BelongsTo
    {
        return $this->belongsTo(Speciality::class);
    }


}

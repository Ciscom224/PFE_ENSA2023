<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Speciality extends Model
{
    use HasFactory;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}

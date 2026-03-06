<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Crypt;

class Teacher extends Authenticatable
{
    use HasFactory;

    // Campos que se pueden rellenar masivamente
    protected $fillable = [
        'full_name',
        'email',
        'is_admin'
    ];

    // Mutator y Accessor para full_name
    public function setFullNameAttribute($value)
    {
        $this->attributes['full_name'] = Crypt::encryptString($value);
    }

    public function getFullNameAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    // Mutator y Accessor para email
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = Crypt::encryptString($value);
    }

    public function getEmailAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    // Relación con permisos de baño
    public function permissions() {
        return $this->hasMany(BathroomPermission::class);
    }
}
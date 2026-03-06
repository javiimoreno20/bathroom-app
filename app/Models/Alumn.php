<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Alumn extends Model
{
    use HasFactory;

    // Campos que se pueden rellenar masivamente
    protected $fillable = ['full_name', 'course_id'];

    // Mutator para cifrar el nombre
    public function setFullNameAttribute($value)
    {
        $this->attributes['full_name'] = Crypt::encryptString($value);
    }

    // Accessor para descifrar el nombre
    public function getFullNameAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    // Relación con cursos
    public function course() {
        return $this->belongsTo(Course::class);
    }

    // Relación con permisos de baño
    public function bathroomPermissions() {
        return $this->hasMany(BathroomPermission::class);
    }
}
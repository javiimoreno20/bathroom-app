<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    //

    use HasFactory;

    //Permite cambios en los campos añadidos.
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    //Los campos añadidos se quedan ocultos.
    protected $hidden = [
        'password'
    ];

    //Relación con otra tabla.
    public function permissions() {
        return $this->hasMany(BathroomPermission::class);
    }
}

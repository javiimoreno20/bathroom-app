<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Teacher extends Authenticatable
{
    use HasFactory;

    /**
     * Contraseña por defecto para administradores
     */
    public const DEFAULT_ADMIN_PASSWORD = 'admin123';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'full_name',
        'email',
        'is_admin',
        'password'
    ];

    /**
     * Relación con permisos de baño
     */
    public function permissions()
    {
        return $this->hasMany(BathroomPermission::class);
    }

    /**
     * ENCRIPTACIÓN nombre
     */
    public function setFullNameAttribute($value)
    {
        $this->attributes['full_name'] = Crypt::encryptString($value);
    }

    public function getFullNameAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * ENCRIPTACIÓN email
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = Crypt::encryptString($value);
    }

    public function getEmailAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    /**
     * HASH de contraseña
     */
    public function setPasswordAttribute($value)
    {
        if (!$value) {
            $this->attributes['password'] = null;
            return;
        }

        // Evita re-hashear si ya viene hasheada
        if (str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = $value;
            return;
        }

        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * IMPORTANTE: nunca exponer password en JSON
     */
    protected $hidden = [
        'password',
    ];
}
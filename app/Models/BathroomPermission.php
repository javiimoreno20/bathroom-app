<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BathroomPermission extends Model
{
    //

    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'returned_at'
    ];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}

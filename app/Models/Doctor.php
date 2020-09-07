<?php

namespace App\Models;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'specialty', 'image'
    ];

    /**
     * Get the doctor's appointments.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

<?php

namespace App\Models;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'blood_type', 'image'
    ];

    public static function boot()
    {
        parent::boot();

        self::deleting(function (Patient $patient) {
            foreach ($patient->appointments as $appointment) {
                $appointment->delete();
            }
        });
    }

    /**
     * Get the patient's appointments.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

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

    public static function boot()
    {
        parent::boot();

        self::deleting(function (Doctor $doctor) {
            foreach ($doctor->appointments as $appointment) {
                $appointment->delete();
            }
        });
    }

    /**
     * Get the doctor's appointments.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
// use App\Http\Services\AppointmentService;

class DashboardController extends Controller
{

    // protected $appointmentService;

    // public function __construct(AppointmentService $appointmentService)
    // {
    //     $this->appointmentService = $appointmentService;
    // }

    public function index()
    {
        $user = auth()->user();

        // $appointments = $this->appointmentService->getPendingAppointments();
        // $confirmed = $this->appointmentService->getConfirmedAppointments(null, true);
        // $ended = $this->appointmentService->getEndedAppointments(null, null, true);

        $appointments = collect();
        $confirmed = 0;
        $ended = 0;

        return view('dashboard', compact('user', 'appointments', 'confirmed', 'ended'));
    }

    public function config()
    {
        $user = auth()->user();

        return view('config', compact('user'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Services\AppointmentService;

class DashboardController extends Controller
{

    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index()
    {
        $user = auth()->user();

        $appointments = $this->appointmentService->getAppointments();
        $confirmed = $this->appointmentService->getConfirmedAppointments(true);
        $ended = $this->appointmentService->getEndedAppointments(true);

        return view('dashboard', compact('user', 'appointments', 'confirmed', 'ended'));
    }

    public function config()
    {
        $user = auth()->user();

        return view('config', compact('user'));
    }
}

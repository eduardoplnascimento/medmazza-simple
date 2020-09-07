<?php

namespace App\Http\Services;

use DB;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Http\Services\Responses\ServiceResponse;
use App\Http\Services\Params\Appointments\StoreServiceParams;

class AppointmentService
{
    protected $appointmentModel;

    public function __construct(Appointment $appointmentModel)
    {
        $this->appointmentModel = $appointmentModel;
    }

    public function getAppointments()
    {
        $appointments = $this->appointmentModel
            ->where('status', '!=', 'cancelled')
            ->where('end_date', '>', Carbon::now()->toDateTimeString())
            ->orderBy('start_date')
            ->get();

        return $appointments;
    }

    public function getEndedAppointments(bool $isCount = false)
    {
        $query = $this->appointmentModel
            ->where('status', 'confirmed')
            ->where('end_date', '<', Carbon::now()->toDateTimeString());

        if ($isCount) {
            return $query->count();
        }

        return $query->orderBy('end_date', 'desc')->get();
    }

    public function getConfirmedAppointments(bool $isCount = false)
    {
        $query = $this->appointmentModel->where('status', 'confirmed');

        if ($isCount) {
            return $query->count();
        }

        return $query->orderBy('start_date', 'desc')->get();
    }

    public function loadAllAppointments(?string $startDate = null, ?string $endDate = null)
    {
        $query = $this->appointmentModel->where('status', '!=', 'cancelled');

        if (!is_null($startDate)) {
            $query = $query->where(
                'start_date',
                '>=',
                Carbon::parse($startDate)->toDateTimeString()
            );
        }

        if (!is_null($endDate)) {
            $query = $query->where(
                'end_date',
                '<=',
                Carbon::parse($endDate)->toDateTimeString()
            );
        }

        return $query
            ->join('doctors', 'doctors.id', 'appointments.doctor_id')
            ->get([
                'appointments.id',
                'doctors.name as title',
                'start_date as start',
                'end_date as end',
                DB::raw('CONCAT("bg-c-", color, " border-none") AS classNames'),
                DB::raw('CONCAT("/appointments/", appointments.id) AS url'),
            ]);
    }

    public function store(string $startDate, int $doctorId, int $patientId)
    {
        try {
            $endDate = Carbon::parse($startDate)->addHours(1)->toDateTimeString();
            $startDate = Carbon::parse($startDate)->toDateTimeString();

            $storeParams = new StoreServiceParams(
                $patientId,
                $doctorId,
                $startDate,
                $endDate
            );

            $appointment = $this->appointmentModel->create($storeParams->toArray());
        } catch (\Throwable $th) {
            return new ServiceResponse(
                false,
                'Erro ao criar agendamento',
                null,
                $th
            );
        }

        return new ServiceResponse(
            true,
            'Agendamento criado com sucesso',
            $appointment
        );
    }

    public function cancel(int $appointmentId)
    {
        try {
            $appointment = $this->appointmentModel->find($appointmentId);

            $appointment->status = 'cancelled';
            $appointment->color = 'red';
            $appointment->save();
        } catch (\Throwable $th) {
            return new ServiceResponse(
                false,
                'Erro ao cancelar agendamento',
                null,
                $th
            );
        }

        return new ServiceResponse(
            true,
            'Agendamento cancelado com sucesso',
            $appointment
        );
    }

    public function destroy(int $appointmentId)
    {
        try {
            $appointment = $this->appointmentModel->find($appointmentId);

            $appointment->delete();
        } catch (\Throwable $th) {
            return new ServiceResponse(
                false,
                'Erro ao deletar agendamento',
                null,
                $th
            );
        }

        return new ServiceResponse(
            true,
            'Agendamento deletar com sucesso',
            $appointment
        );
    }
}

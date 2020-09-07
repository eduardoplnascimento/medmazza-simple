<?php

namespace App\Http\Services;

use Carbon\Carbon;
use App\Models\Doctor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Services\Responses\ServiceResponse;

class DoctorService
{
    protected $doctorModel;

    public function __construct(Doctor $doctorModel)
    {
        $this->doctorModel = $doctorModel;
    }

    public function store(Request $request): ServiceResponse
    {
        try {
            $doctor = $this->doctorModel->create([
                'name'       => $request->name,
                'email'      => $request->email,
                'specialty'  => $request->specialty,
            ]);

            // Verifica se informou o arquivo e se é válido
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Define um aleatório para o arquivo baseado no timestamps atual
                $name = Str::random(6);
                $extension = $request->image->extension();
                $nameFile = "{$name}.{$extension}";

                // Faz o upload:
                $upload = $request->image->storeAs('img/pictures', $nameFile);

                // Verifica se NÃO deu certo o upload (Redireciona de volta)
                if (!$upload) {
                    return redirect()
                        ->back()
                        ->withError('Falha ao fazer upload da imagem')
                        ->withInput();
                }

                $doctor->image = $nameFile;
                $doctor->save();
            }
        } catch (\Throwable $th) {
            return new ServiceResponse(
                false,
                'Erro ao editar usuário!',
                null,
                $th
            );
        }

        return new ServiceResponse(
            true,
            'Usuário editado com sucesso!',
            $doctor
        );
    }

    public function update(int $doctorId, Request $request): ServiceResponse
    {
        try {
            $doctor = $this->doctorModel->find($doctorId);

            // Verifica se informou o arquivo e se é válido
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Define um aleatório para o arquivo baseado no timestamps atual
                $name = Str::random(6);
                $extension = $request->image->extension();
                $nameFile = "{$name}.{$extension}";

                // Faz o upload:
                $upload = $request->image->storeAs('img/pictures', $nameFile);

                // Verifica se NÃO deu certo o upload (Redireciona de volta)
                if (!$upload) {
                    return redirect()
                        ->back()
                        ->withError('Falha ao fazer upload da imagem')
                        ->withInput();
                }

                $doctor->image = $nameFile;
            }

            $doctor->name = $request->name;
            $doctor->email = $request->email;
            $doctor->specialty = $request->specialty;
            $doctor->save();
        } catch (\Throwable $th) {
            return new ServiceResponse(
                false,
                'Erro ao editar usuário!',
                null,
                $th
            );
        }

        return new ServiceResponse(
            true,
            'Usuário editado com sucesso!',
            $doctor
        );
    }

    public function destroy(int $doctorId): ServiceResponse
    {
        try {
            $doctor = $this->doctorModel->find($doctorId);
            $doctor->delete();
        } catch (\Throwable $th) {
            return new ServiceResponse(
                false,
                'Erro ao remover usuário!',
                null,
                $th
            );
        }

        return new ServiceResponse(
            true,
            'Usuário removido com sucesso!',
            $doctor
        );
    }

    public function getAvailableByDate(string $date): ServiceResponse
    {
        try {
            $date = Carbon::parse($date)->toDateTimeString();

            $doctors = $this->doctorModel
                ->whereNotIn('id', function ($query) use ($date) {
                    $query->from('appointments')
                        ->select('appointments.doctor_id')
                        ->where('appointments.start_date', $date)
                        ->where('status', '!=', 'cancelled')
                        ->whereNull('deleted_at');
                })
                ->get(['id', 'name', 'image', 'doctors.specialty']);
        } catch (\Throwable $th) {
            return new ServiceResponse(
                false,
                'Erro ao buscar médicos!',
                null,
                $th
            );
        }

        return new ServiceResponse(
            true,
            'Busca por médicos com sucesso!',
            $doctors
        );
    }
}

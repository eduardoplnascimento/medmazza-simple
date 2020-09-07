<?php

namespace App\Http\Services;

use Carbon\Carbon;
use App\Models\Patient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Services\Responses\ServiceResponse;

class PatientService
{
    protected $patientModel;

    public function __construct(Patient $patientModel)
    {
        $this->patientModel = $patientModel;
    }

    public function store(Request $request): ServiceResponse
    {
        try {
            $patient = $this->patientModel->create([
                'name'       => $request->name,
                'email'      => $request->email,
                'blood_type' => $request->blood,
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

                $patient->image = $nameFile;
                $patient->save();
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
            $patient
        );
    }

    public function update(int $patientId, Request $request): ServiceResponse
    {
        try {
            $patient = $this->patientModel->find($patientId);

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

                $patient->image = $nameFile;
            }

            $patient->name = $request->name;
            $patient->email = $request->email;
            $patient->blood_type = $request->blood;
            $patient->save();
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
            $patient
        );
    }

    public function destroy(int $patientId): ServiceResponse
    {
        try {
            $patient = $this->patientModel->find($patientId);
            $patient->delete();
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
            $patient
        );
    }

    public function getAvailableByDate(string $date): ServiceResponse
    {
        try {
            $date = Carbon::parse($date)->toDateTimeString();

            $patients = $this->patientModel
                ->whereNotIn('id', function ($query) use ($date) {
                    $query->from('appointments')
                        ->select('appointments.patient_id')
                        ->where('appointments.start_date', $date)
                        ->where('status', '!=', 'cancelled');
                })
                ->get(['id', 'name', 'image', 'blood_type']);
        } catch (\Throwable $th) {
            return new ServiceResponse(
                false,
                'Erro ao buscar pacientes!',
                null,
                $th
            );
        }

        return new ServiceResponse(
            true,
            'Busca por pacientes com sucesso!',
            $patients
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Services\PatientService;
use App\Models\Patient;

class PatientController extends Controller
{
    protected $patientModel;
    protected $patientService;

    public function __construct(
        Patient $patientModel,
        PatientService $patientService
    ) {
        $this->patientModel = $patientModel;
        $this->patientService = $patientService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $patients = $this->patientModel->orderBy('name')->get();

        return view('patients', compact('user', 'patients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        return view('patient-create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storeResponse = $this->patientService->store($request);

        if (!$storeResponse->success) {
            return redirect(route('patients.index'))->withError('Erro ao criar paciente!');
        }

        return redirect(route('patients.index'))->withSuccess('Paciente criado com sucesso!');
    }

    /**
     * Display and show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $user = auth()->user();

        $patient = $this->patientModel->find($id);

        return view('patient', compact('user', 'patient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $updateResponse = $this->patientService->update($id, $request);

        if (!$updateResponse->success) {
            return redirect(route('patients.show', $id))->withError('Erro ao editar paciente!');
        }

        return redirect(route('patients.show', $id))->withSuccess('Paciente editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $destroyResponse = $this->patientService->destroy($id);

        if (!$destroyResponse->success) {
            return redirect(route('patients.index'))->withError('Erro ao remover paciente!');
        }

        return redirect(route('patients.index'))->withSuccess('Paciente removido com sucesso!');
    }

    /**
     * Get patients available on specified date.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAvailableByDate(Request $request)
    {
        $serviceResponse = $this->patientService->getAvailableByDate(
            $request->date
        );

        if (!$serviceResponse->success) {
            return response()->json($serviceResponse->errors);
        }

        return response()->json($serviceResponse->data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Services\DoctorService;
use App\Models\Doctor;

class DoctorController extends Controller
{
    protected $doctorModel;
    protected $doctorService;

    public function __construct(
        Doctor $doctorModel,
        DoctorService $doctorService
    ) {
        $this->doctorModel = $doctorModel;
        $this->doctorService = $doctorService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $doctors = $this->doctorModel->orderBy('name')->get();

        return view('doctors', compact('user', 'doctors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        return view('doctor-create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storeResponse = $this->doctorService->store($request);

        if (!$storeResponse->success) {
            return redirect(route('doctors.index'))->withError('Erro ao criar médico!');
        }

        return redirect(route('doctors.index'))->withSuccess('Médico criado com sucesso!');
    }

    /**
     * Display and how the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $user = auth()->user();
        $doctor = $this->doctorModel->find($id);

        return view('doctor', compact('user', 'doctor'));
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
        $updateResponse = $this->doctorService->update($id, $request);

        if (!$updateResponse->success) {
            return redirect(route('doctors.show', $id))->withError('Erro ao editar médico!');
        }

        return redirect(route('doctors.show', $id))->withSuccess('Médico editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $destroyResponse = $this->doctorService->destroy($id);

        if (!$destroyResponse->success) {
            return redirect(route('doctors.index'))->withError('Erro ao remover médico!');
        }

        return redirect(route('doctors.index'))->withSuccess('Médico removido com sucesso!');
    }

    /**
     * Get doctors available on specified date.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAvailableByDate(Request $request)
    {
        $serviceResponse = $this->doctorService->getAvailableByDate(
            $request->date
        );

        if (!$serviceResponse->success) {
            return response()->json($serviceResponse->errors);
        }

        return response()->json($serviceResponse->data);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Doctor;

class DoctorController extends Controller
{
    protected $doctorModel;

    public function __construct(Doctor $doctorModel)
    {
        $this->doctorModel = $doctorModel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = $this->doctorModel->with('appointments')->get();

        return response()->json($doctors);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $doctor = $this->doctorModel->with('appointments')->find($id);

        return response()->json($doctor);
    }
}

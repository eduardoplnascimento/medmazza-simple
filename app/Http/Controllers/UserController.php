<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Services\UserService;

class UserController extends Controller
{
    protected $userModel;
    protected $userService;

    public function __construct(UserService $userService, User $userModel)
    {
        $this->userModel = $userModel;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $admins = $this->userModel->orderBy('name')->get();

        return view('admins', compact('user', 'admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        return view('admin-create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storeResponse = $this->userService->store($request);

        if (!$storeResponse->success) {
            return redirect(route('admins.index'))->withError('Erro ao criar administrador!');
        }

        return redirect(route('admins.index'))->withSuccess('Administrador criado com sucesso!');
    }

    /**
     * Display specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $user = auth()->user();
        $admin = $this->userModel->find($id);

        return view('admin', compact('user', 'admin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->user();
        return view('config', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $updateResponse = $this->userService->update($user->id, $request);

        if (!$updateResponse->success) {
            return redirect(route('users.edit', $user->id))->withError('Erro ao editar usuário!');
        }

        return redirect(route('users.edit', $user->id))->withSuccess('Usuário editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $destroyResponse = $this->userService->destroy($id);

        if (!$destroyResponse->success) {
            return redirect(route('admins.index'))->withError('Erro ao remover administrador!');
        }

        return redirect(route('admins.index'))->withSuccess('Administrador removido com sucesso!');
    }
}

<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\Responses\ServiceResponse;

class UserService
{
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function store(Request $request): ServiceResponse
    {
        try {
            $user = $this->userModel->create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'api_token' => Str::random(80)
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

                $user->image = $nameFile;
                $user->save();
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
            $user
        );
    }

    public function update(int $userId, Request $request): ServiceResponse
    {
        try {
            $user = $this->userModel->find($userId);

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

                $user->image = $nameFile;
            }

            $user->name = $request->name;
            $user->save();
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
            $user
        );
    }

    public function destroy(int $userId): ServiceResponse
    {
        try {
            $user = $this->userModel->find($userId);
            $user->delete();
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
            $user
        );
    }
}

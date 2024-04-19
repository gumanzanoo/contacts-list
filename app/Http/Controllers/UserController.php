<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistryRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class UserController extends Controller
{
    public function __construct(protected User $user)
    {}

    /*
     * Irá validar os dados de entrar utilizando form request, e irá criar um novo usuário
     * devolvendo um token de autenticação logo em seguida.
     *
     * @param UserRegistryRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(UserRegistryRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            /** @var User $user */
            $user = $this->user::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            $user->createToken('user_access_token')->plainTextToken;

            return response()->json(['message' => 'Usuário cadastrado com sucesso.', 'data' => $validated], 201);
        } catch (Throwable $th) {
            return response()->json(['message' => 'Erro ao cadastrar usuário.'], 500);
        }
    }

    public function changePassword(): JsonResponse
    {
        try {
            /** @var User $user */
            $user = auth()->user();
            $user->password = bcrypt(request('password'));
            $user->save();

            return response()->json(['message' => 'Senha alterada com sucesso.']);
        } catch (Throwable $th) {
            return response()->json(['message' => 'Erro ao alterar senha.'], 500);
        }
    }

    public function changeEmail(): JsonResponse
    {
        try {
            /** @var User $user */
            $user = auth()->user();
            $user->email = request('email');
            $user->save();

            return response()->json(['message' => 'Email alterado com sucesso.']);
        } catch (Throwable $th) {
            return response()->json(['message' => 'Erro ao alterar email.'], 500);
        }
    }
}

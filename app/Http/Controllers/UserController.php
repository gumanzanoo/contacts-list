<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistryRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Throwable;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
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

            return response()->json([
                'message' => 'Usuário cadastrado com sucesso.',
                'access_token' => $user->createToken('user_access_token')->plainTextToken
            ], 201);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
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
            log($th->getTraceAsString());
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
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao alterar email.'], 500);
        }
    }
}

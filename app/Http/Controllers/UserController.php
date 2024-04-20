<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\UserRegistryRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
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

            return response()->json([
                'message' => 'Cadastro concluído.',
                'access_token' => $user->createToken('user_access_token')->plainTextToken
            ], 201);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao realizar cadastro.'], 500);
        }
    }

    public function destroy(DeleteAccountRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            /** @var User $user */
            $user = $request->user();
            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json(['message' => 'Senha inválida.'], 403);
            }

            $user->delete();
            return response()->json(['message' => 'Conta cancelada com sucesso.']);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao deletar conta.'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credenciais = $request->only(['email', 'password']);

            if (!auth()->attempt($credenciais)) {
                return response()->json(['error' => 'Email ou senha incorretos.'], 403);
            }

            return response()->json([
                'message' => 'Login efetuado com sucesso.',
                'access_token' => $request->user()->createToken('user_access_token')->plainTextToken,
                'token_type' => 'Bearer',
            ], 201);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao efetuar login.'], 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    public function refresh(Request $request): JsonResponse
    {
        return response()->json([
            'access_token' => $request->user()->createToken('user_access_token')->plainTextToken
        ]);
    }
}

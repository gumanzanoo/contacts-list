<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class AuthController extends Controller
{

    /**
     * Login do usuário.
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credenciais = $request->only(['email', 'password']);

            if (!$token = auth()->guard('api')->attempt($credenciais)) {
                return response()->json(['error' => 'Forbidden'], 403);
            }

            return $this->respondWithToken($token);
        } catch (Throwable $th) {
            return response()->json(['message' => 'Erro ao efetuar login.'], 500);
        }
    }

    /**
     * Logout do usuário.
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    private function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }
}

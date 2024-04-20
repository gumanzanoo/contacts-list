<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRecoverRequest;
use App\Http\Requests\RecoverPasswordRequest;
use App\Models\User;
use App\Services\AccountRecoverService;
use Illuminate\Http\JsonResponse;
use Throwable;

class AccountRecoverController extends Controller
{
    public function __construct(protected AccountRecoverService $accountRecoverService)
    {}

    /**
     * @param AccountRecoverRequest $request
     * @return JsonResponse
     */
    public function accountRecoverRequest(AccountRecoverRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $this->accountRecoverService->sendRecoverEmail($validated['email']);
            return response()->json(['message' => 'E-mail enviado com sucesso.']);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao enviar e-mail.'], 500);
        }
    }

    public function passwordRecover(RecoverPasswordRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            /** @var ?User $user */
            $user = $this->accountRecoverService->changePassword(
                $validated['email'], $validated['password'], $validated['token']);

            if (!$user) {
                return response()->json(['message' => 'Token inválido.'], 403);
            }

            return response()->json([
                'message' => 'Senha alterada com sucesso.',
                'access_token' => $user->createToken('user_access_token')->plainTextToken
            ]);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao recuperar conta.'], 500);
        }
    }}

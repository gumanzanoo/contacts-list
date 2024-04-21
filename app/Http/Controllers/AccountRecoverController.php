<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRecoverRequest;
use App\Http\Requests\RecoverPasswordRequest;
use App\Jobs\MailerJob;
use App\Mail\AccountRecover;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Throwable;

class AccountRecoverController extends Controller
{
    public function __construct(protected User $user)
    {}

    /**
     * @param AccountRecoverRequest $request
     * @return JsonResponse
     */
    public function accountRecoverRequest(AccountRecoverRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            /** @var User $user */
            $user = $this->user::getUserByEmail($validated['email']);
            MailerJob::dispatch($validated['email'], new AccountRecover(Password::broker()->createToken($user)));
            return response()->json(['message' => 'E-mail enviado com sucesso.'], 201);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao enviar e-mail.'], 500);
        }
    }

    public function passwordRecover(RecoverPasswordRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            /** @var User $user */
            $user = $this->user::getUserByEmail($validated['email']);
            if (!Password::broker()->tokenExists($user, $validated['token'])) {
                return response()->json(['message' => 'Token inválido.'], 403);
            }

            $user->password = bcrypt($validated['password']);
            $user->save();

            return response()->json([
                'message' => 'Senha alterada com sucesso.',
                'access_token' => $user->createToken('user_access_token')->plainTextToken
            ]);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao recuperar conta.'], 500);
        }
    }
}

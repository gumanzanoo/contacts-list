<?php

namespace Tests\Feature;

use App\Jobs\MailerJob;
use App\Mail\AccountRecover;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AccountRecoveryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testando processo de recuperação de conta.
     * Fluxo: Criação do usuário fake. -> Requisição para recuperação de conta
     * -> Envio de email com link para formulário e Token. -> Recuperação de conta. -> Login com nova senha.
     */
    public function test_account_recovery_email_dispatch(): void
    {
        Queue::fake();

        // Criação do usuário
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password')
        ]);

        // Requisição para recuperação de conta
        $response = $this->postJson(env('APP_URL') . '/api/account-recover', [
            'email' => $user->email,
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'E-mail enviado com sucesso.']);

        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);

        // Verifica se o job com email foi despachado
        Queue::assertPushed(MailerJob::class);

        Mail::fake();

        // Verifica se o envio do email de recuperação de conta está funcionando
        Mail::to($user->email)->send(new AccountRecover('token'));
        Mail::assertSent(AccountRecover::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        // Verificando a recuperação da conta
        $token = Password::broker()->createToken($user);

        $response = $this->patchJson(env('APP_URL') . '/api/account-recover', [
            'email' => $user->email,
            'token' => $token,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Senha alterada com sucesso.']);

        $response = $this->postJson(env('APP_URL') . '/api/auth', [
            'email' => $user->email,
            'password' => 'newpassword'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['access_token', 'token_type', 'message']);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testando o registro de usuário.
     * Fluxo: Enviar uma requisição POST para a rota /api/user com os dados de um usuário.
     * -> Verificar a resposta e a estrutura do JSON. -> Verificar se o usuário foi criado no banco de dados.
     */
    public function test_user_registration()
    {
        $response = $this->postJson(env('APP_URL') . '/api/user', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Cadastro concluído.'
            ])
            ->assertJsonStructure([
                'access_token'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com'
        ]);
    }

    /**
     * Testando a exclusão de usuário.
     * Fluxo: Criar um usuário. -> Autenticar esse usuário. -> Tentar deletar a conta com a senha correta.
     * -> Verificar a resposta e se o usuário foi removido do banco de dados.
     */
    public function test_user_deletion()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password')
        ]);

        $token = $user->createToken('user_access_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson(env('APP_URL') . '/api/user', [
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Conta cancelada com sucesso.']);

        $this->assertDatabaseMissing('users', [
            'email' => 'john@example.com'
        ]);
    }
}

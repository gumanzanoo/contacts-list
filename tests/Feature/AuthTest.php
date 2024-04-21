<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testando o login com sucesso.
     */
    public function test_login_success()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson(env('APP_URL') . '/api/auth', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['access_token', 'token_type', 'message']);
    }

    /**
     * Testando o login com falha.
     */
    public function test_login_failure()
    {
         User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson(env('APP_URL') . '/api/auth', [
            'email' => 'user@example.com',
            'password' => 'wrong'
        ]);

        $response->assertStatus(403)
            ->assertJson(['error' => 'Email ou senha incorretos.']);
    }

    /**
     * Testando o logout.
     */
    public function test_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('user_access_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete(env('APP_URL') . '/api/auth');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logout realizado com sucesso.']);
    }

    /**
     * Testando os detalhes do usuário (me).
     */
    public function test_user_details()
    {
        $user = User::factory()->create();
        $token = $user->createToken('user_access_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(env('APP_URL') . '/api/auth');

        $response->assertStatus(200)
            ->assertJson(['email' => $user->email]);
    }

    /**
     * Testando o refresh token.
     */
    public function test_refresh_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('user_access_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put(env('APP_URL') . '/api/auth');

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token']);
    }
}

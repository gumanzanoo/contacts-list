<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class GetAdressTest extends TestCase
{
    /**
     * Testando a busca de endereço por CEP e CEP por endereço.
     * Fluxo: Cria um usuário falso. -> Busca endereço por CEP. -> Verifica se o endereço foi encontrado.
     * -> Busca CEP por endereço. -> Verifica se o CEP foi encontrado.
     */
    public function test_example(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password')
        ]);

        $token = $user->createToken('user_access_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(env('APP_URL') . '/api/adress?cep=80250104');

        $jsonExpected = [
            "message",
            "data"
        ];

        $response->assertStatus(200)
            ->assertJsonStructure($jsonExpected)
            ->assertJson(["message" => "Endereço encontrado."]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(env('APP_URL') . '/api/cep?uf=PR&city=Curitiba&street=Pasteur');

        $response->assertStatus(200)
            ->assertJsonStructure($jsonExpected)
            ->assertJson(["message" => "CEP encontrado."]);
    }
}

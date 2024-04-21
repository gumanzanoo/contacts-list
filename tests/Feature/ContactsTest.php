<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\ContactAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Criar o usuário para o teste.
     */
    private function createUserAndToken(): array
    {
        $user = User::factory()->create();
        $token = $user->createToken('user_access_token')->plainTextToken;
        $contact = Contact::factory()->create(['user_id' => $user->id]);
        ContactAddress::factory()->create(['contact_id' => $contact->id]);

        return [$user, $token];
    }

    /**
     * Testa se a rota index retorna a lista de contatos.
     */
    public function test_index_returns_contact_list()
    {
        [$user, $token] = $this->createUserAndToken();
        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson(env('APP_URL') . '/api/contact');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Listagem de contatos.');
    }

    /**
     * Testa se a rota show retorna os detalhes do contato.
     */
    public function test_show_returns_contact_details()
    {
        [$user, $token] = $this->createUserAndToken();
        $contact = $user->contacts()->first();

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson(env('APP_URL') . "/api/contact/{$contact->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Contato encontrado.')
            ->assertJsonPath('data.name', $contact->name);
    }

    /**
     * Testa se a rota store cria um contato.
     */
    public function test_store_creates_contact()
    {
        [$user, $token] = $this->createUserAndToken();

        $contactData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'cpf' => '31096901013',
            'cep' => '12345678',
            'state' => 'Some State',
            'city' => 'Some City',
            'street' => 'Some Street',
            'number' => '1234',
            'complement' => 'Some Complement',
            'latitude' => '12.345678',
            'longitude' => '98.765432'
        ];

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson(env('APP_URL') . '/api/contact', $contactData);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Contato criado.')
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email']
            ]);
    }

    /**
     * Testa se a rota update modifica um contato.
     */
    public function test_update_modifies_contact()
    {
        [$user, $token] = $this->createUserAndToken();
        $contact = $user->contacts()->first();
        $updatedData = ['name' => 'New Name', 'cep' => '85950157', 'cpf' => '20024579050'];

        $contactAdress = $contact->address()->first();
        $arrContact = $contact->toArray();
        $arrContact['name'] = $updatedData['name'];
        $arrContact['email'] = $contact->email;
        $arrContact['cpf'] = $updatedData['cpf'];
        $arrContact['phone'] = $contact->phone;
        $arrContact['cep'] = $updatedData['cep'];
        $arrContact['state'] = $contactAdress->state;
        $arrContact['city'] = $contactAdress->city;
        $arrContact['street'] = $contactAdress->street;
        $arrContact['number'] = $contactAdress->number;
        $arrContact['complement'] = $contactAdress->complement;
        $arrContact['latitude'] = $contactAdress->latitude;
        $arrContact['longitude'] = $contactAdress->longitude;

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/contact/{$contact->id}", $arrContact);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Contato atualizado.')
            ->assertJsonPath('data.name', 'New Name');
    }

    /**
     * Testa se a rota destroy deleta um contato.
     */
    public function test_destroy_deletes_contact()
    {
        [$user, $token] = $this->createUserAndToken();
        $contact = $user->contacts()->first();

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/contact/{$contact->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Contato deletado.');

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    /**
     * Testa se a rota search encontra contatos.
     */
    public function test_search_finds_contacts()
    {
        [$user, $token] = $this->createUserAndToken();
        Contact::factory()->create(['name' => 'John Doe', 'user_id' => $user->id]);
        Contact::factory()->create(['cpf' => '11122233344', 'user_id' => $user->id]);

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/contact/search/query?search=John");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Contatos encontrados.');

        $response = $this->actingAs($user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/contact/search/query?search=11122233344");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Contatos encontrados.');
    }
}

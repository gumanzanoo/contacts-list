<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateContactRequest;
use App\Models\Contact;
use App\Models\ContactAddress;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ContactController
{
    public function __construct(protected Contact $contact, protected ContactAddress $contactAddress)
    {}

    public function index(Request $request): JsonResponse
    {
        try {
            /** @var User $user */
            $user = $request->user();
            $contacts = $user->contacts()->with(
                'address:id,contact_id,cep,state,city,street,number,complement,latitude,longitude'
            )->orderBy('contacts.name')->get();
            return response()->json(['message' => 'Listagem de contatos.', 'data' => $contacts]);
        } catch (Throwable) {
            return response()->json(['message' => 'Erro ao listar contatos.'], 500);
        }
    }

    public function store(CreateContactRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            /** @var Contact $contact */
            $contact = $this->contact::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'cpf' => $validated['cpf']
            ]);

            $contact->address()->create([
                'cep' => $validated['cep'],
                'state' => $validated['state'],
                'city' => $validated['city'],
                'street' => $validated['street'],
                'number' => $validated['number'],
                'complement' => $validated['complement'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude']
            ]);

            return response()->json(['message' => 'Contato criado.']);
        } catch (Throwable) {
            return response()->json(['message' => 'Erro ao criar contato.'], 500);
        }
    }

    public function show(Contact $contact): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Contato encontrado.',
                'data' => $contact->load('address')
            ]);
        } catch (Throwable) {
            return response()->json(['message' => 'Erro ao encontrar contato.'], 500);
        }
    }

    public function update(Request $request)
    {
        return response()->json(['message' => 'Contato atualizado.']);
    }

    public function destroy(Contact $contact): JsonResponse
    {
        try {
            $contact->delete();
            return response()->json(['message' => 'Contato deletado.']);
        } catch (Throwable) {
            return response()->json(['message' => 'Erro ao deletar contato.'], 500);
        }
    }
}

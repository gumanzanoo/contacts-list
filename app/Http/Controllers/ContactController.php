<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateContactRequest;
use App\Http\Requests\UpdateContactRequest;
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
            $page = $request->query('page', 1);
            $per_page = $request->query('per_page', 10);

            /** @var User $user */
            $user = $request->user();
            $contacts = $user->contacts()->with(
                'address:id,contact_id,cep,state,city,street,number,complement,latitude,longitude'
            )->orderBy('contacts.name')->paginate($per_page, ['*'], 'page', $page);
            return response()->json(['message' => 'Listagem de contatos.', 'data' => $contacts]);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao listar contatos.'], 500);
        }
    }

    public function store(CreateContactRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            /** @var User $user */
            $user = $request->user();

            /** @var Contact $contact */
            $contact = $user->contacts()->create([
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

            return response()->json(['message' => 'Contato criado.', 'data' => $contact->load('address')]);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao criar contato.'], 500);
        }
    }

    public function show(int $contactId): JsonResponse
    {
        try {
            $contact = $this->contact::query()->find($contactId);
            if (!$contact) {
                return response()->json(['message' => 'Contato não encontrado.'], 404);
            }

            return response()->json([
                'message' => 'Contato encontrado.',
                'data' => $contact->load('address')
            ]);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao encontrar contato.'], 500);
        }
    }

    public function update(int $id, UpdateContactRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            /** @var Contact $contact */
            $contact = $this->contact::query()->find($id);
            if (!$contact) {
                return response()->json(['message' => 'Contato não encontrado.'], 404);
            }

            $contact->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'cpf' => $validated['cpf']
            ]);

            $contact->address()->update([
                'cep' => $validated['cep'],
                'state' => $validated['state'],
                'city' => $validated['city'],
                'street' => $validated['street'],
                'number' => $validated['number'],
                'complement' => $validated['complement'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude']
            ]);

            return response()->json(['message' => 'Contato atualizado.', 'data' => $contact->load('address')]);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao atualizar contato.'], 500);
        }
    }

    public function destroy(int $contactId): JsonResponse
    {
        try {
            $contact = $this->contact::query()->find($contactId);
            $contact->delete();
            return response()->json(['message' => 'Contato deletado.']);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao deletar contato.'], 500);
        }
    }
}

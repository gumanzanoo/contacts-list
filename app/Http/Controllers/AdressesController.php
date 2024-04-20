<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdressRequestByCEP;
use App\Http\Requests\CEPRequestByAdress;
use App\Services\Contracts\IAdressApi;
use Illuminate\Http\JsonResponse;
use Throwable;

class AdressesController
{
    public function __construct(protected IAdressApi $adressApi)
    {}

    public function getAddressByCEP(AdressRequestByCEP $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $response = $this->adressApi->getAddressByCEP($validated['cep']);

            if (!$response) return response()->json(['message' => 'CEP não encontrado.'], 404);
            return response()->json(['message' => 'Endereço encontrado.', 'data' => $response]);
        } catch (Throwable) {
            return response()->json(['message' => 'Erro ao buscar endereço.'], 500);
        }
    }

    public function getCepByAdress(CEPRequestByAdress $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $response = $this->adressApi->getCepByAdress($validated['uf'], $validated['city'], $validated['street']);
            if (!$response) return response()->json(['message' => 'Endereço não encontrado.'], 404);
            return response()->json(['message' => 'CEP encontrado.', 'data' => $response]);
        } catch (Throwable) {
            return response()->json(['message' => 'Erro ao buscar CEP.'], 500);
        }
    }
}

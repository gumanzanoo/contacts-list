<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdressRequestByCEP;
use App\Http\Requests\CEPRequestByAdress;
use App\Services\Contracts\IAdressApi;
use App\Services\Contracts\IGeolocationApi;
use Illuminate\Http\JsonResponse;
use Throwable;

class AdressesController
{
    public function __construct(protected IAdressApi $adressApi, protected IGeolocationApi $geolocationApi)
    {}

    private function getCoordinates(string $uf, string $city, string $street): array|null
    {
        try {
            $response = $this->geolocationApi->getCoordinates($uf . ', ' . $city . ', ' . $street);
            if (!$response) return null;
            return $response;
        } catch (Throwable $th) {

            log($th->getTraceAsString());
            return null;
        }
    }

    public function getAddressByCEP(AdressRequestByCEP $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $response = $this->adressApi->getAddressByCEP($validated['cep']);
            if (!$response) return response()->json(['message' => 'CEP não encontrado.'], 404);
            $coordinates = $this->getCoordinates($response['uf'], $response['localidade'], $response['logradouro']);
            if ($coordinates) $response['coordinates'] = $coordinates;
            return response()->json(['message' => 'Endereço encontrado.', 'data' => $response]);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao buscar endereço.'], 500);
        }
    }

    public function getCepByAdress(CEPRequestByAdress $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $response = $this->adressApi->getCepByAdress($validated['uf'], $validated['city'], $validated['street']);
            if (!$response) return response()->json(['message' => 'Endereço não encontrado.'], 404);
            $coordinates = $this->getCoordinates($response['uf'], $response['localidade'], $response['logradouro']);
            if ($coordinates) $response['coordinates'] = $coordinates;
            return response()->json(['message' => 'CEP encontrado.', 'data' => $response]);
        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return response()->json(['message' => 'Erro ao buscar CEP.'], 500);
        }
    }
}

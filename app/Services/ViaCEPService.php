<?php

namespace App\Services;

use App\Services\Contracts\IAdressApi;
use Illuminate\Support\Facades\Http;

class ViaCEPService implements IAdressApi
{
    const URL = "https://viacep.com.br/ws/";

    public function getAddressByCEP(string $cep): array|null
    {
        $url = self::URL . "$cep/json/";

        $response = Http::get($url);

        if ($response->status() === 404 || isset($response['erro'])) {
            return null;
        }

        return json_decode($response, true);
    }

    public function getCepByAdress(string $uf, string $city, string $street): array|null
    {
        $url = self::URL . "$uf/$city/$street/json/";

        $response = Http::get($url);

        if ($response->status() === 404 || isset($response['erro'])) {
            return null;
        }

        return json_decode($response, true);
    }
}

<?php

namespace App\Services\Contracts;

interface IAdressApi
{
    public function getAddressByCEP(string $cep): array|null;
    public function getCepByAdress(string $uf, string $city, string $street): array|null;
}

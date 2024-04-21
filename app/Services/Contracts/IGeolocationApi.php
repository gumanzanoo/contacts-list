<?php

namespace App\Services\Contracts;

interface IGeolocationApi
{
    public function getCoordinates(string $address): array|null;
}

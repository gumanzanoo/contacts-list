<?php

namespace App\Services;

use App\Services\Contracts\IGeolocationApi;
use Illuminate\Support\Facades\Http;
use Throwable;

class GeolocationService implements IGeolocationApi
{
    const URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    public function getCoordinates(string $address): array|null
    {
        try {
            $response = Http::get(self::URL, [
                'address' => $address,
                'key' => env('GOOGLE_MAPS_API_KEY')
            ]);

            if ($response->status() != 200) {
                return null;
            }

            $data = $response->json();

            if (empty($data['results']) || $data['status'] != 'OK') {
                return null;
            }

            $location = $data['results'][0]['geometry']['location'];
            return [
                'latitude' => $location['lat'],
                'longitude' => $location['lng']
            ];

        } catch (Throwable $th) {
            log($th->getTraceAsString());
            return null;
        }
    }
}

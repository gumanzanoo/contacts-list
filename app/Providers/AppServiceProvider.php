<?php

namespace App\Providers;

use App\Services\Contracts\IAdressApi;
use App\Services\Contracts\IGeolocationApi;
use App\Services\GeolocationService;
use App\Services\ViaCEPService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IAdressApi::class, ViaCEPService::class);
        $this->app->bind(IGeolocationApi::class, GeolocationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

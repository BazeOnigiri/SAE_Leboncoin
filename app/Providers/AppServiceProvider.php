<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\Reservation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurer le binding pour les réservations avec la clé primaire personnalisée
        Route::bind('reservation', function ($value) {
            return Reservation::where('idreservation', $value)->firstOrFail();
        });
    }
}

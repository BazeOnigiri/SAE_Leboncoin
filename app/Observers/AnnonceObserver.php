<?php

namespace App\Observers;

use App\Models\Annonce;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AnnonceObserver
{
    public function saved(Annonce $annonce): void
    {
        // On recharge les relations pour être sûr d'avoir l'adresse complète
        $annonce->load('adresse.ville');
        
        if (!$annonce->adresse) return;

        $cacheKey = 'gps_v2_adresse_' . $annonce->idadresse;
        $apiKey = env('GOOGLE_MAPS_API_KEY');

        if (empty($apiKey)) return;

        $adresse = sprintf('%s %s, %s %s, France', 
            $annonce->adresse->numerorue, 
            $annonce->adresse->nomrue, 
            $annonce->adresse->ville->codepostal, 
            $annonce->adresse->ville->nomville
        );

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $adresse,
                'key' => $apiKey
            ]);

            $data = $response->json();

            if ($response->successful() && !empty($data['results'])) {
                $geometry = $data['results'][0]['geometry']['location'];

                Cache::forever($cacheKey, [
                    'lat' => $geometry['lat'],
                    'lng' => $geometry['lng']
                ]);
            }
        } catch (\Exception $e) {}
    }

    public function created(Annonce $annonce): void {}
    public function updated(Annonce $annonce): void {}
    public function deleted(Annonce $annonce): void {}
    public function restored(Annonce $annonce): void {}
    public function forceDeleted(Annonce $annonce): void {}
}
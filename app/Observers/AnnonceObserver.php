<?php

namespace App\Observers;

use App\Models\Annonce;

class AnnonceObserver
{
        public function saved(Annonce $annonce): void
    {
        $annonce->load('adresse.ville');
        if (!$annonce->adresse) return;

        $cacheKey = 'gps_v2_adresse_' . $annonce->idadresse;
        $adresse = $annonce->adresse->nomrue . ' ' . $annonce->adresse->ville->nomville;

        try {
            $res = \Illuminate\Support\Facades\Http::withHeaders(['User-Agent' => 'SaeLbc'])
                ->get('https://nominatim.openstreetmap.org/search', ['q' => $adresse, 'format' => 'json', 'limit' => 1]);
            
            if (!empty($res->json())) {
                \Illuminate\Support\Facades\Cache::forever($cacheKey, [
                    'lat' => $res->json()[0]['lat'], 'lng' => $res->json()[0]['lon']
                ]);
            }
        } catch (\Exception $e) {}
    }
    
    /**
     * Handle the Annonce "created" event.
     */
    public function created(Annonce $annonce): void
    {
        //
    }

    /**
     * Handle the Annonce "updated" event.
     */
    public function updated(Annonce $annonce): void
    {
        //
    }

    /**
     * Handle the Annonce "deleted" event.
     */
    public function deleted(Annonce $annonce): void
    {
        //
    }

    /**
     * Handle the Annonce "restored" event.
     */
    public function restored(Annonce $annonce): void
    {
        //
    }

    /**
     * Handle the Annonce "force deleted" event.
     */
    public function forceDeleted(Annonce $annonce): void
    {
        //
    }
}

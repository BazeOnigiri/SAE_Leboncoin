<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Annonce;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CacheGeocoding extends Command
{
    protected $signature = 'geo:cache';
    protected $description = 'Géocode toutes les annonces via Google Maps et met en cache';

    public function handle()
    {
        $annonces = Annonce::with('adresse.ville')->get();
        $bar = $this->output->createProgressBar(count($annonces));
        $apiKey = env('GOOGLE_MAPS_API_KEY'); // Récupération de la clé

        if (empty($apiKey)) {
            $this->error("La clé API Google Maps n'est pas configurée dans le .env");
            return;
        }
        
        foreach ($annonces as $annonce) {
            if (!$annonce->adresse) { $bar->advance(); continue; }

            $cacheKey = 'gps_v2_adresse_' . $annonce->idadresse;

            // On force la mise à jour si vous voulez tout recalculer, sinon gardez le if(!Cache::has...)
            // Ici je mets le if pour éviter de payer l'API pour rien si c'est déjà fait
            if (!Cache::has($cacheKey)) {
                
                // Formatage de l'adresse pour Google
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
                    // Google a un rate limit plus élevé, mais une petite pause est toujours prudente
                    usleep(100000); 
                } catch (\Exception $e) {}
            }
            $bar->advance();
        }
        $bar->finish();
        $this->info("\nGeocoding Google terminé !");
    }
}
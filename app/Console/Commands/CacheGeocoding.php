<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Annonce;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CacheGeocoding extends Command
{
    protected $signature = 'geo:cache';
    protected $description = 'Géocode toutes les annonces et met en cache pour toujours';

    public function handle()
    {
        $annonces = Annonce::with('adresse.ville')->get();
        $bar = $this->output->createProgressBar(count($annonces));
        
        foreach ($annonces as $annonce) {
            if (!$annonce->adresse) { $bar->advance(); continue; }

            $cacheKey = 'gps_v2_adresse_' . $annonce->idadresse;

            if (!Cache::has($cacheKey)) {
                $adresse = sprintf('%s %s, %s %s', 
                    $annonce->adresse->numerorue, $annonce->adresse->nomrue, 
                    $annonce->adresse->ville->codepostal, $annonce->adresse->ville->nomville
                );

                try {
                    $response = Http::withHeaders(['User-Agent' => 'SaeLbc/1.0'])
                        ->get('https://nominatim.openstreetmap.org/search', [
                            'q' => $adresse, 'format' => 'json', 'limit' => 1
                        ]);

                    if ($response->successful() && !empty($response->json())) {
                        Cache::forever($cacheKey, [
                            'lat' => $response->json()[0]['lat'],
                            'lng' => $response->json()[0]['lon']
                        ]);
                    }
                    usleep(600000); 
                } catch (\Exception $e) {}
            }
            $bar->advance();
        }
        $bar->finish();
        $this->info("\nCache terminé !");
    }
}
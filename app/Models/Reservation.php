<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    protected $table = 'reservation'; 
    
    protected $primaryKey = 'idreservation';
    public $timestamps = false;

    protected $fillable = [
        'idannonce',
        'idutilisateur',
        'iddatedebutreservation',
        'iddatefinreservation',
        'nomclient',
        'prenomclient',
        'telephoneclient',
        'nombrevoyageur',
        'adultes',
        'enfants',
        'bebes',
    ];
    
    public function annonce() {
        return $this->belongsTo(Annonce::class, 'idannonce');
    }
    
    public function dateDebut() {
        return $this->belongsTo(Date::class, 'iddatedebutreservation', 'iddate');
    }

    public function dateFin() {
        return $this->belongsTo(Date::class, 'iddatefinreservation', 'iddate');
    }

    public function transaction() {
        return $this->hasOne(Transaction::class, 'idreservation', 'idreservation');
    }

    public function messages() {
        return $this->hasMany(Message::class, 'idreservation', 'idreservation')
            ->orderBy('created_at', 'asc');
    }

    public function messagesNonLusPour($userId) {
        return $this->messages()
            ->where('idutilisateurreceveur', $userId)
            ->where('lu', false)
            ->count();
    }

    public function utilisateur() {
        return $this->belongsTo(User::class, 'idutilisateur', 'idutilisateur');
    }

    public function getEstPasseeAttribute()
    {
        if ($this->dateFin && $this->dateFin->date) {
            return Carbon::parse($this->dateFin->date)->isPast();
        }
        return false;
    }

    public function resteAPayerSurPlace(?Annonce $annonce = null): float
    {
        $annonce = $annonce ?? $this->annonce;
        if (!$annonce) {
            return 0.0;
        }

        $startDate = $this->dateDebut?->date;
        $endDate = $this->dateFin?->date;
        if (!$startDate || !$endDate) {
            return 0.0;
        }

        $dateDebut = Carbon::parse($startDate);
        $dateFin = Carbon::parse($endDate);
        $nbNuits = max(1, $dateDebut->diffInDays($dateFin));

        $prixNuitee = (float) ($annonce->prixnuitee ?? 0);
        $totalRent = $prixNuitee * $nbNuits;

        $adultsCount = (int) ($this->adultes ?? $this->nombrevoyageur ?? 1);
        $touristTax = 4.00 * $nbNuits * max(1, $adultsCount);

        $reste = ($totalRent * 0.65);

        return max(0.0, round($reste, 2));
    }
}
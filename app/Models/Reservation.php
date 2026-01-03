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
}
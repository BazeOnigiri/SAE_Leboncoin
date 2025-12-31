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

    public function getEstPasseeAttribute()
    {
        if ($this->dateFin && $this->dateFin->date) {
            return Carbon::parse($this->dateFin->date)->isPast();
        }
        return false;
    }
}
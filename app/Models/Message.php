<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'message';
    
    protected $primaryKey = 'idmessage';
    
    public $timestamps = false;

    protected $fillable = [
        'idutilisateurreceveur',
        'iddate',
        'idutilisateurexpediteur',
        'contenumessage',
        'idreservation',
        'lu',
        'created_at',
    ];

    protected $casts = [
        'lu' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function expediteur()
    {
        return $this->belongsTo(User::class, 'idutilisateurexpediteur', 'idutilisateur');
    }

    public function receveur()
    {
        return $this->belongsTo(User::class, 'idutilisateurreceveur', 'idutilisateur');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation', 'idreservation');
    }

    public function date()
    {
        return $this->belongsTo(Date::class, 'iddate', 'iddate');
    }

    public function scopeNonLus($query)
    {
        return $query->where('lu', false);
    }

    public function scopePourReservation($query, $idReservation)
    {
        return $query->where('idreservation', $idReservation);
    }
}

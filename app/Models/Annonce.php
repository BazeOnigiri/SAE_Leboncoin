<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    protected $table = 'annonce';
    protected $primaryKey = 'idannonce';
    public $timestamps = false;

    /* Une annonce a beaucoup ou pas de ... */

    public function photos()
    {
        return $this->hasMany(Photo::class, 'idannonce');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'idannonce');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'idannonce');
    }

    /* Une annonce se réfere à 1 ... */

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }

    public function typehebergement()
    {
        return $this->belongsTo(TypeHebergement::class, 'idtypehebergement');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'idutilisateur');
    }

    public function heuredepart()
    {
        return $this->belongsTo(Heure::class, 'idheuredepart');
    }

    public function heurearrivee()
    {
        return $this->belongsTo(Heure::class, 'idheurearrivee');
    }

    public function date()
    {
        return $this->belongsTo(Date::class, 'iddate');
    }

    /* Une annonce se réfere à beaucoup ou pas de ... */

    public function chambres()
    {
        return $this->belongsToMany(Chambre::class, 'disposer', 'idannonce', 'idchambre');
    }

    public function commodites()
    {
        return $this->belongsToMany(Commodite::class, 'proposer', 'idannonce', 'idcommodite');
    }

    public function annonces()
    {
        return $this->belongsToMany(Annonce::class, 'ressembler', 'idannonce_a', 'idannonce_b');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'favoriser', 'idannonce', 'idutilisateur');
    }

    public function dates()
    {
        return $this->belongsToMany(Date::class, 'relier', 'idannonce', 'iddate')->withPivot('estdisponible');
    }
    public function datePublication()
    {
        return $this->belongsTo(Date::class, 'iddate');
    }
}

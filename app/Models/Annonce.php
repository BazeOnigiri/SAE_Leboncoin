<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    protected $table = 'annonce';
    protected $primaryKey = 'idannonce';
    public $timestamps = false;

    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }

    public function chambre()
    {
        return $this->belongsToMany(Chambre::class, 'disposer', 'idannonce', 'idchambre');
    }

    public function photo()
    {
        return $this->hasMany(Photo::class, 'idannonce');
    }
    
    public function typeexterieur()
    {
        return $this->belongsToMany(TypeExterieur::class, 'presenter', 'idannonce', 'idtypeexterieur');
    }

    public function typeservice()
    {
        return $this->belongsToMany(TypeService::class, 'offrir', 'idannonce', 'idtypeservice');
    }

    public function typeequipement()
    {
        return $this->belongsToMany(TypeEquipement::class, 'proposer', 'idannonce', 'idtypeequipement');
    }

    public function typehebergement()
    {
        return $this->belongsTo(TypeHebergement::class, 'idtypehebergement');
    }

}

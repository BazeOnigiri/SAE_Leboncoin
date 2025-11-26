<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commodite extends Model
{
    protected $table = 'commodite';
    protected $primaryKey = 'idcommodite';
    public $timestamps = false;

    /* Une commodite se réfere à 1 ... */

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'idcategorie');
    }

    /* Une commodite se réfere à beaucoup ou pas de ... */

    public function annonces()
    {
        return $this->belongsToMany(Annonce::class, 'proposer', 'idcommodite', 'idannonce');
    }

    public function recherches()
    {
        return $this->belongsToMany(Recherche::class, 'filtrer', 'idcommodite', 'idrecherche');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commodite extends Model
{
    protected $table = 'commodite';
    protected $primaryKey = 'idcommodite';
    public $timestamps = false;

    /* Une table_actuelle se réfere à 1 ... */

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'idcategorie');
    }

    /* Une table_actuelle se réfere à beaucoup ou pas de ... */

    public function annonce()
    {
        return $this->belongsToMany(Annonce::class, 'proposer', 'idcommodite', 'idannonce');
    }

    public function recherche()
    {
        return $this->belongsToMany(Recherche::class, 'filtrer', 'idcommodite', 'idrecherche');
    }
}
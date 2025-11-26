<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeHebergement extends Model
{
    protected $table = 'typehebergement';
    protected $primaryKey = 'idtypehebergement';
    public $timestamps = false;

    /* Un type d'hebergement a beaucoup ou pas de ... */

    public function annonces()
    {
        return $this->hasMany(Modele_referee::class, 'idtypehebergement');
    }

    /* Un type d'hebergement se réfere à 1 ... */

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'idcategorie');
    }

    /* Un type d'hebergement se réfere à beaucoup ou pas de ... */

    public function recherches()
    {
        return $this->belongsToMany(Recherche::class, 'cibler', 'idtypehebergement', 'idrecherche');
    }
}

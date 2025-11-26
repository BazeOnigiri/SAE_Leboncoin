<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    protected $table = 'ville';
    protected $primaryKey = 'idville';

    public $timestamps = false;

    /* Une ville a beaucoup ou pas de ... */

    public function adresse()
    {
        return $this->hasMany(Adresse::class, 'idville');
    }

    public function recherche()
    {
        return $this->hasMany(Recherche::class, 'idville');
    }

    /* Une ville se réfere à 1 ... */

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'iddepartement');
    }
}


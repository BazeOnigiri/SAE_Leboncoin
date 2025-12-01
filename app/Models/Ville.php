<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    protected $table = 'ville';
    protected $primaryKey = 'idville';
    public $timestamps = false;
    public function adresse()
    {
        return $this->hasMany(Adresse::class, 'idville');
    }

    public function recherche()
    {
        return $this->hasMany(Recherche::class, 'idville');
    }
    public function departement()
    {
        return $this->belongsTo(Departement::class, 'iddepartement');
    }
}


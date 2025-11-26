<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    protected $table = 'departement';
    protected $primaryKey = 'iddepartement';
    public $timestamps = false;

    /* Un departement a beaucoup ou pas de ... */

    public function villes()
    {
        return $this->hasMany(Ville::class, 'iddepartement');
    }

    /* Un departement se réfere à 1 ... */

    public function region()
    {
        return $this->belongsTo(Region::class, 'idregion');
    }
}
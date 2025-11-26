<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'region';
    protected $primaryKey = 'idregion';
    public $timestamps = false;

    /* Une region a beaucoup ou pas de ... */

    public function recherches()
    {
        return $this->hasMany(Recherche::class, 'idregion');
    }

    public function departements()
    {
        return $this->hasMany(Departement::class, 'idregion');
    }
}

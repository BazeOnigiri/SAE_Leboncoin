<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/* push */

class Chambre extends Model
{
    protected $table = 'chambre';
    protected $primaryKey = 'idchambre';
    public $timestamps = false;

    /* Une chambre se réfere à beaucoup ou pas de ... */

    public function annonces()
    {
        return $this->belongsToMany(Annonce::class, 'disposer', 'idchambre', 'idannonce');
    }
}

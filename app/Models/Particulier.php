<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Particulier extends Model
{
    protected $table = 'particulier';
    protected $primaryKey = 'idutilisateur';
    public $timestamps = false;

    protected $guarded = [];

    /* Un particulier se réfere à 1 ... */

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'idutilisateur');
    }

    public function dateNaissance() {
        return $this->belongsTo(\App\Models\Date::class, 'iddate');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professionnel extends Model
{
    protected $table = 'professionnel';
    protected $primaryKey = 'idutilisateur';
    public $timestamps = false;

    protected $guarded = [];

    /* Un professionnel se réfere à 1 ... */

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'idutlisateur');
    }
}

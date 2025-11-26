<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/* push */

class Adresse extends Model
{
    protected $table = 'adresse';
    protected $primaryKey = 'idadresse';
    public $timestamps = false;

    /* Une adresse a beaucoup ou pas de ... */

    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'idadresse');
    }

    public function utilisateurs()
    {
        return $this->hasMany(User::class, 'idadresse');
    }

    /* Une adresse se réfere à 1 ... */

    public function ville()
    {
        return $this->belongsTo(Ville::class, 'idville');
    }
}

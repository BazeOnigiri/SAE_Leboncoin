<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adresse extends Model
{
    protected $table = 'adresse';
    protected $primaryKey = 'idadresse';
    public $timestamps = false;
    protected $guarded = [];

    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'idadresse');
    }

    public function utilisateurs()
    {
        return $this->hasMany(User::class, 'idadresse');
    }
    public function ville()
    {
        return $this->belongsTo(Ville::class, 'idville');
    }   
}

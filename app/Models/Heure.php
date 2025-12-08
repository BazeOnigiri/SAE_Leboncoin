<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Heure extends Model
{
    protected $table = 'heure';
    protected $primaryKey = 'idheure';
    public $timestamps = false;

    protected $fillable = [
        'heure',
    ];

    public function annoncesDepart()
    {
        return $this->hasMany(Annonce::class, 'idheuredepart');
    }

    public function annoncesArrivee()
    {
        return $this->hasMany(Annonce::class, 'idheurearrivee');
    }
}
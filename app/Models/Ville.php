<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    protected $table = 'ville';
    protected $primaryKey = 'idville';

    public $timestamps = false;

    protected $fillable = [
        'iddepartement',
        'codepostal',
        'nomville',
        'taxedesejour'
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'iddepartement');
    }

    public function adresse()
    {
        return $this->hasMany(Adresse::class, 'idadresse');
    }
}


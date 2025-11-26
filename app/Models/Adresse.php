<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adresse extends Model
{
    protected $table = 'adresse';
    protected $primaryKey = 'idadresse';
    public $timestamps = false;

    public function ville()
    {
        return $this->belongsTo(Ville::class, 'idville', 'idville');
    }
}

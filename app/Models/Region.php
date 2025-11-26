<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'region';
    protected $primaryKey = 'idregion';
    public $timestamps = false;

    public function departements()
    {
        return $this->hasMany(Departement::class, 'idregion');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeEquipement extends Model
{
    protected $table = 'typeequipement';
    protected $primaryKey = 'idtypeequipement';
    public $timestamps = false;

    // Une catégorie a plusieurs commodités
    public function commodites()
    {
        return $this->hasMany(Commodite::class, 'idtypeequipement');
    }
}

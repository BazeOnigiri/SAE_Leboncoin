<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = 'categorie';
    protected $primaryKey = 'idcategorie';
    public $timestamps = false;

    /**
     * Une catégorie a plusieurs commodités.
     */
    public function commodites()
    {
        return $this->hasMany(Commodite::class, 'idcategorie');
    }
}
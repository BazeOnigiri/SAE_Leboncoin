<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professionnel extends Model
{
    protected $table = 'professionnel';
    protected $primaryKey = 'idutilisateur';
    public $timestamps = false;
    public $incrementing = false;

}

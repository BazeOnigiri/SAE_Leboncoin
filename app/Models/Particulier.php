<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Particulier extends Model
{
    protected $table = 'particulier';
    protected $primaryKey = 'idutilisateur';
    public $timestamps = false;
    public $incrementing = false;

}

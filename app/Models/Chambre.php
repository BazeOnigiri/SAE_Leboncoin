<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    protected $table = 'chambre';
    protected $primaryKey = 'idchambre';

    public $timestamps = false;
}

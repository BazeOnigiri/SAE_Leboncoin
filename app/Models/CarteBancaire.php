<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarteBancaire extends Model
{
    protected $table = 'cartebancaire';
    protected $primaryKey = 'idcartebancaire';

    public $timestamps = false;

    protected $guarded = [];
}

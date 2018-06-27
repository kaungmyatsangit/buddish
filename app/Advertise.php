<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertise extends Model
{
    protected $fillable=['latitude','longtitude','name','description'];
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pagoda extends Model
{
    protected $fillable=['name','address','phone_number','credit_name','description','latitude','longtitude','image'];
}

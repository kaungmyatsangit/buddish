<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable=['artist_type','artist_image','artist_name','biography'];

    public function post(){
        return $this->hasMany('App\Post');
    }

}

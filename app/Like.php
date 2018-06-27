<?php

namespace App;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable=[
        'client_user_id',
        'post_id',
    ];

    public function client_user(){
        return $this->belongsTo('App\Client_user');
    }

   public function post(){
        return $this->belongsTo('App\Post');
   }

   public function likeusers(){
    return $this->belongsTo('App\Client_user');

}

}

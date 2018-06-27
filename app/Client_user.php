<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client_user extends Model
{
    protected $fillable = [
        'name','phone_number','address','fb_id','udid'
    ];

    public function like(){
        return $this->hasMany('App\Like');
    }

    public function comment(){
        return $this->hasMany('App\Comment');
    }





}

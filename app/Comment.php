<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable=['client_user_id','post_id','description'];

    public function post(){
        return $this->belongsTo('App\Post');
    }

    public function comment_user(){
        return $this->belongsTo('App\Client_user');
    }

}

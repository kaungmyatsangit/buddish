<?php

namespace App;
use Session;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = [
        'admin_id',
        'title',
        'category_id',
        'artist_id',
        'description',
        'image',
        'audio'];


    public function artist()
    {
        return $this->belongsTo('App\Artist');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function likeusers(){
        return $this->belongsTo('App\Client_user');

    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function likes(){
        return $this->hasMany('App\Like');
    }

    public function comment(){
        return $this->hasMany('App\Comment');
    }


}

<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Client_user;
use App\Comment;
use App\Like;
use App\Post;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        $data['post_count']=count(Post::all());
        $data['user_count']=count(Client_user::all());
        $data['comment_count']=count(Comment::all());
        $data['like_count']=count(Like::all());
        $data['author_count']=count(Artist::all());

        $data['artists']=Artist::all();

        return view('admin.index',$data);
    }
}

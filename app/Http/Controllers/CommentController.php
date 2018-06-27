<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Session;

class CommentController extends Controller
{
    public function index(){
        $comments=Comment::all();
        return view('admin/post/comment',compact('comments',$comments));
    }

    public function delete($id){
        Comment::destroy($id);
        Session::flash('success','Comment Delete Success');
        return redirect()->back();
    }

}

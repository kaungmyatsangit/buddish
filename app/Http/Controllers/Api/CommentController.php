<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comment;

class CommentController extends ApiController
{
    public function comment(Request $request)
    {
        $user_id = $request->get('user_id');
        $post_id = $request->get('post_id');
        $comment = $request->get('comment');

        $result = Comment::create([
            'client_user_id' => $user_id,
            'post_id' => $post_id,
            'description' => $comment
        ]);
        if ($request) {
            return response()->json(['status' => 1, 'message' => 'Success']);
        } else {
            return response()->json(['status' => 1, 'message' => 'Unsuccess']);

        }
    }

    public function post_comment(Request $request)
    {
        $post_id=$request->get('post_id');
        $comments = Comment::where('post_id', $post_id)->get();
        $result = array();

        $result['post_id'] = $post_id;
        foreach ($comments as $comment) {
            $out['comment'] = $comment->description;
            $result[] = $out;
            unset($out);
        }
        return response()->json(['data' => collect($result), 'status' => 1]);
    }

    public function comment_delete(Request $request){
        $id=$request->get('id');

        $result=Comment::destroy($id);

        if($result){
            return response()->json(['status'=>1,'message'=>'Success']);

        }

    }

}

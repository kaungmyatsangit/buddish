<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeController extends ApiController
{

    public function like(Request $request)
    {
        $user_id = $request->get('user_id');
        $post_id = $request->get('post_id');

        $checkLike = $this->checkLike($user_id, $post_id);

        if ($checkLike) {
            return response()->json(['message'=>'already like','status'=>0], 200);

        } else {
            $like = new Like();
            $like->client_user_id = $user_id;
            $like->post_id = $post_id;
            $like->save();

            $result = array(
                'user_id' => $user_id,
                'post_id' => $post_id
            );
            return response()->json(['data' => $result, 'status' => 1], 200);

        }

    }

    public function unlike(Request $request){
        $user_id=$request->get('user_id');
        $post_id=$request->get('post_id');

        $result=DB::statement("delete from likes where post_id=$post_id and client_user_id=$user_id");

        if($result){
            return response()->json(['status'=>1,'message'=>'Success']);
        }

    }

    public function checkLike($user_id, $post_id)
    {
        $likes = Like::where('client_user_id', $user_id)->get();
        $like_array=array();
        foreach ($likes as $like) {
           array_push($like_array,$like->post_id);
        }
        if(in_array($post_id,$like_array)){
            return true;
        }else{
            return false;
        }
    }

}

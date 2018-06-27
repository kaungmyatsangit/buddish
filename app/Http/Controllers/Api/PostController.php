<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Like;
use App\Post;
use Illuminate\Http\Request;
use Session;
use DB;


class PostController extends ApiController
{

    public function __construct(Request $request)
    {
        session_start();

//        dd(session('APP_LOCALE'));
    }

    public function index($page)
    {
      $page=$page;
      $perPage=10;
      $offest=($perPage*$page)-$perPage;
        $posts = Post::where('public', 1)->offset($offest)->limit($perPage)->orderBy('id', 'desc')->get();

        $result = array();


        $result[]['count'] = count($posts);

        foreach ($posts as $post) {

            $out['id'] = $post->id;
            $out['author'] = $post->user->username;
            $out['title'] = $post->title;
            $out['category'] = $post->category->title;
            $out['artist'] = $post->artist->artist_name;

            $client_id = $_SESSION['client_id'];

            if ($this->like($client_id, $post->id)) {
                $out['like'] = 1;
            } else {
                $out['like'] = 0;
            }

            $out['like_count'] = $post->likes->count();
            $out['comment_count'] = $post->comment->count();

            $out['created'] = date('m-d-Y', strtotime($post->created_at));

//            $type = explode('.', $post->post_uploader);
            $out['image'] = asset('/post/image') . '/' . $post->image;
            $out['post_level'] = $post->post_level;

            if ($post->post_level == 1) {
                $out['description'] = $post->description;
            } elseif ($post->post_level == 2) {
                $out['audio'] = asset('/post/audio/') . '/' . $post->audio;
            } elseif ($post->post_level == 3) {
                $out['description'] = $post->description;
                $out['audio'] = asset('/post/audio/') . '/' . $post->audio;
            }

            $out['detail'] = url('/api/post/' . $post->id);

            $result[] = $out;
            unset($out);
        }

        return $this->showAll(collect($result));
    }

    public function show($id)
    {
        $post = Post::find($id);
//        dd($post);
        if ($post) {
            $out['id'] = $post->id;
            $out['author'] = $post->user->username;
            $out['title'] = $post->title;
            $out['category'] = $post->category->title;
            $out['artist'] = $post->artist->artist_name;

            $client_id = $_SESSION['client_id'];

            if ($this->like($client_id, $post->id)) {
                $out['like_status'] = 1;
            } else {
                $out['like_status'] = 0;
            }

            $out['like_count'] = $post->likes->count();
            $out['comment_count'] = $post->comment->count();

            $out['image'] = asset('/post/image') . '/' . $post->image;
            $out['post_level'] = $post->post_level;
            if ($post->post_level == 1) {
                $out['description'] = $post->description;
            } elseif ($post->post_level == 2) {
                $out['audio'] = asset('/post/audio/') . $post->audio;
            } elseif ($post->post_level == 3) {
                $out['description'] = $post->description;
                $out['audio'] = asset('/post/audio/') . $post->audio;
            }

            $out['description'] = $post->description;
            $out['created'] = date('m-d-Y', strtotime($post->created_at));


            return response()->json(['data' => $out, 'status' => 1, 'code' => 200], 200);
        } else {
            return $this->errorResponse('No artist', 0, 404);

        }
    }

    public function like($client_id, $post_id)
    {
//        dd($client_id);
        $result = DB::select("select * from likes where client_user_id=$client_id and post_id=$post_id");
        return $result;
    }


}

<?php

namespace App\Http\Controllers;

use App\Artist;
use Auth;
use App\Post;
use Session;
use Validator;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use File;

class PostController extends Controller
{
    public function index()
    {
        $data['posts'] = Post::all();
        return view('admin/post/index', $data);
    }

    public function create()
    {
        $data['categories'] = Category::all();
        $data['artists'] = Artist::all();
        return view('admin/post/post_create', $data);
    }

    public function store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);
        if (!$validate->fails()) {
            $post_level = 0;
            $audio = false;
            $audioname = '';
            $description = '';
            $description_type = false;

            if ($request->hasFile('audio')) {
                $audio = true;
                $audioFile = $request->file('audio');
                $audioname = $audioFile->getClientOriginalExtension();
                $audioname = uniqid() . '.' . $audioname;
                $audioFile->move(public_path() . '/post/audio/', $audioname);
            } else {
                $audio = false;
            }

            if (!empty($request->get('description'))) {
                $description = $request->get('description');
                $description_type = true;
            } else {
                $description = false;
            }
            $imagename='';
            if(!empty($request->get('image'))){
              $image = $request->file('image');
              $imagename = uniqid() . '.' . $image->getClientOriginalExtension();

              $destinationPath = public_path('/post/image');

              $img = Image::make($request->file('image')->getRealPath());

              $img->resize(150, 150, function ($constraint) {
                  $constraint->aspectRatio();
              })->save($destinationPath . '/' . $imagename);
            }


            if ($description_type && $audio) {
                $post_level = 3;
            } elseif (!$description_type && $audio) {
                $post_level = 2;
            } elseif (!$audio && $description_type) {
                $post_level = 1;
            }

            $post = new Post();
            $post->user_id = Auth::user()->id;
            $post->title = $request->get('title');
            $post->image = $imagename;
            $post->description = $description;
            $post->audio = $audioname;
            $post->category_id = $request->get('category');
            $post->artist_id = $request->get('artist');
            $post->save();

            $lastId = $post->id;
            $post = Post::find($lastId);
            $post->post_level = $post_level;
            $post->save();

            Session::flash('success', 'Post Create Success');
            return redirect()->back();

        } else {
            Session::flash('error', 'Something went wrong');
            return redirect()->back();
        }

    }

    public function delete($id)
    {
        $post_delete = Post::find($id)->first();
        $image_path = public_path('/post/image/' . $post_delete->image);
        File::delete($image_path);

        if ($post_delete->post_level == 3 or $post_delete->post_level == 2) {
            $image_path = public_path('/post/audio/' . $post_delete->audio);
            File::delete($image_path);
        }

        $post = Post::destroy($id);
        Session::flash('success', 'Post Delete Success');
        return redirect()->back();
    }

    public function edit($id)
    {
        $post = Post::find($id);
        return view('admin/post/edit', compact('post', $post));
    }


    public function publish($id)
    {
        $post = Post::find($id);
        $post->public = 1;
        $post->save();
        Session::flash('success', 'Post Publish Success');
        return redirect()->back();
    }

    public function unpublish($id)
    {
        $post = Post::find($id);
        $post->public = 0;
        $post->save();
        Session::flash('success', 'Post unpublish Success');
        return redirect()->back();
    }

    public function detail($id){
        $post=Post::find($id);
        return view('admin.post.detail',compact('post',$post));
    }

}

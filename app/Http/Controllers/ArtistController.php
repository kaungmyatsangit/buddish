<?php

namespace App\Http\Controllers;

use App\Artist;
use File;
use Illuminate\Queue\RedisQueue;
use Session;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ArtistController extends Controller
{
    public function index()
    {
        $data['artists']=Artist::all();
        return view('admin/artist/index',$data);
    }

    public function create()
    {
        return view('admin/artist/create');
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'biography' => 'required'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        } else {
            $name = $request->get('name');
            $type = $request->get('type');
            $biography = $request->get('biography');
            $image = $request->file('file');
            $imagename = uniqid() . '.' . $image->getClientOriginalExtension();


            $destinationPath = public_path('/artist');

            $img = Image::make($request->file('file')->getRealPath());

            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $imagename);

           if( Artist::create([
               'artist_type'=>$type,
               'artist_image'=>$imagename,
               'artist_name'=>$name,
               'biography'=>$biography
           ])){
               Session::flash('success','Artist Create Success');
               return redirect('/admin/artist');
           }else{
               Session::flash('error','Something Wrong');
               return redirect()->back();
           }


        }
    }

    public function delete($id){
        $artist_delete=Artist::find($id)->first();
        $image_path=public_path('/artist/'.$artist_delete->artist_image);

        File::delete($image_path);

        $artist=Artist::destroy($id);
        Session::flash('success','Artist Delete Success');
        return redirect()->back();
    }

    public function edit($id){
        $artist=Artist::find($id);
        return view('/admin/artist/edit',compact('artist',$artist));
    }

    public function update(Request $request,$id){
        $imagename='';
        $artist=Artist::find($id);
        if($request->file('file')){
            $image_path=public_path('/artist/'.$artist->artist_image);
            File::delete($image_path);

            $image = $request->file('file');
            $imagename = uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/artist');
            $img = Image::make($request->file('file')->getRealPath());
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $imagename);
        }else{
            $imagename=$artist->artist_image;
        }

        $name = $request->get('name');
        $type = $request->get('type');
        $biography = $request->get('biography');

        $artist->artist_type=$type;
        $artist->artist_image=$imagename;
        $artist->artist_name=$name;
        $artist->biography=$biography;
        $artist->save();
        Session::flash('success','Artist Create Success');
        return redirect('/admin/artist');

    }

}

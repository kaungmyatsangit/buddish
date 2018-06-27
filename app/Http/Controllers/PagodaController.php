<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pagoda;
use Intervention\Image\Facades\Image;
use File;
use Session;

class PagodaController extends Controller
{
    public function index(){
      $pagodas=Pagoda::all();
      return view('admin/pagoda/index',compact('pagodas',$pagodas));
    }


    public function create(){
      return view('admin/pagoda/create');
    }

    public function store(Request $request){
      $name=$request->get('name');
      $address=$request->get('address');
      $description=$request->get('description');
      $credit_name=$request->get('credit_name');
      $phone_number=$request->get('phone_number');
      $latitude=$request->get('latitude');
      $longtitude=$request->get('longtitude');


      $destinationPath = public_path('/pagoda');

      $images=$request->file('image');
      $imagename=[];

      foreach($images as $image){
        $image_name= uniqid() . '.' . $image->getClientOriginalExtension();
        $imagename[]=$image_name;
        $img = Image::make($image->getRealPath());
        $img->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $image_name);
         unset($image_name);
      }

      Pagoda::create([
        'name'=>$name,
        'address'=>$address,
        'description'=>$description,
        'credit_name'=>$credit_name,
        'phone_number'=>$phone_number,
        'latitude'=>$latitude,
        'longtitude'=>$longtitude,
        'image'=>implode(',',$imagename)
      ]);

      Session::flash('success','Create Success');
      return redirect('/admin/pagoda');

      }

     public function edit($id){
        $pagoda=Pagoda::find($id);
        return view('admin/pagoda/edit',compact('pagoda',$pagoda));
      }
      
      
        public function update($id,Request $request){
        $pagoda=Pagoda::find($id);
        $imagename=[];
        $destinationPath = public_path('/pagoda');

        if($request->has('image')){
          $img=explode(',',$pagoda->image);
          for ($i=0;$i<count($img);$i++) {
            File::delete(public_path('/pogada/'.$img[$i]));
          }

          $images=$request->file('image');

          foreach($images as $image){
            $image_name= uniqid() . '.' . $image->getClientOriginalExtension();
            $imagename[]=$image_name;
            $img = Image::make($image->getRealPath());
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $image_name);
             unset($image_name);
          }

        }



        $name=$request->get('name');
        $address=$request->get('address');
        $description=$request->get('description');
        $credit_name=$request->get('credit_name');
        $phone_number=$request->get('phone_number');
        $latitude=$request->get('latitude');
        $longtitude=$request->get('longtitude');

        $pagoda->name=$name;
        $pagoda->address=$address;
        $pagoda->description=$description;
        $pagoda->credit_name=$credit_name;
        $pagoda->phone_number=$phone_number;
        $pagoda->latitude=$latitude;
        $pagoda->longtitude=$longtitude;
          if($request->has('image')){
            $pagoda->image=implode(',',$imagename);
        }else{
          $pagoda->image=$pagoda->image;
        }
        $pagoda->active=$request->get('active');
        $pagoda->save();

        Session::flash('success','Update Success');
          return redirect('/admin/pagoda');

      }

    public function delete($id){
        $pagoda=Pagoda::find($id);
        $images=array();
        $img=explode(',',$pagoda->image);
            for($i=0;$i<count($img);$i++) {
          File::delete(public_path('/pagoda/'.$img[$i]));
          
        }

        $pagoda->delete();
        Session::flash('success','Delete Success');
        return redirect()->back();

      }



}

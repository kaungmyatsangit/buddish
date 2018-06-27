<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Advertise;
use Session;
class AdvertiseController extends Controller
{
    public function index(){
      $advertises=Advertise::all();
      return view('admin/advertise/index',compact('advertises',$advertises));
    }

    public function create(){
      return view('admin/advertise/create');
    }

    public function store(Request $request){
      $name=$request->get('name');
      $latitude=$request->get('latitude');
      $longtitude=$request->get('longtitude');
      $description=$request->get('description');

      Advertise::create([
        'name'=>$name,
        'latitude'=>$latitude,
        'longtitude'=>$longtitude,
        'description'=>$description
      ]);

      Session::flash('success','Create Success');
        return redirect('admin/advertise');
    }

    public function edit($id){
      $advertise=Advertise::find($id);
      return view('admin/advertise/edit',compact('advertise',$advertise));
    }

    public function update($id,Request $request){
      $advertise=Advertise::find($id);
      $advertise->name=$request->get('name');
      $advertise->latitude=$request->get('latitude');
      $advertise->longtitude=$request->get('longtitude');
      $advertise->description=$request->get('description');
      $advertise->active=$request->get('active');
      $advertise->save();
      Session::flash('success','Update Success');
      $advertises=Advertise::all();
      return view('admin/advertise/index',compact('advertises',$advertises));
    }

    public function delete($id){
      Advertise::destroy($id);
      Session::flash('success', 'Delete Success');
      return redirect()->back();
    }
}

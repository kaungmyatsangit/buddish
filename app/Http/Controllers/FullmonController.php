<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fullmon;
use Session;

class FullmonController extends Controller
{
    public function index(){
      $fullmons=Fullmon::all();
      return view('admin/fullmon/index',compact('fullmons',$fullmons));
    }


    public function create(){
      return view('admin/fullmon/create');
    }

    public function store(Request $request){
      $date=$request->get('date');
      $message=$request->get('message');
      $name=$request->get('name');

      $result=Fullmon::create([
        'name'=>$name,
        'message'=>$message,
        'date'=>$date
      ]);

      if($result){
        Session::flash('success','Create Success');
        return redirect()->back();
      }
    }

    public function edit($id){
      $fullmon=Fullmon::find($id);

      return view('admin/fullmon/edit',compact('fullmon',$fullmon));
    }


    public function update($id,Request $request){
      $fullmon=Fullmon::find($id);
      $fullmon->date=$request->get('date');
      $fullmon->name=$request->get('name');
      $fullmon->message=$request->get('message');
      $fullmon->active=$request->get('active');
      $fullmon->save();
      Session::flash('success','Update Success');
      $fullmons=Fullmon::all();
      return view('admin/fullmon/index',compact('fullmons',$fullmons));
    }

    public function delete($id){
      Fullmon::destroy($id);
      Session::flash('success', 'Delete Success');
      return redirect()->back();
    }

}

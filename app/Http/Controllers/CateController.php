<?php

namespace App\Http\Controllers;

use Validator;
use Session;
use App\Category;
use Illuminate\Http\Request;

class CateController extends Controller
{
    public function index(){
        $data['categories']=Category::all();
        return view('admin/cate/index',$data);
    }

    public function create(Request $request){
        $validator=Validator::make($request->all(),[
            'title'=>'required'
        ]);

        if(!$validator->fails()){
            $result=Category::create([
                'title'=>$request->get('title')
            ]);

           if($result) {
               Session::flash('success', 'Category Create Success');
               return redirect('/admin/cate');
           }

        }else{
            Session::flash('error','Category Name Required');
            return redirect('/admin/cate');

        }

    }

    public function delete($id){
        $cate=Category::destroy($id);

        Session::flash('success','Category Delete Success');
        return redirect()->back();

    }

    public function edit(Request $request,$id){
        $category=Category::find($id)->first();
        $category->title=$request->get('title');
        $category->save();
        Session::flash('success','Category Update Success');
        return redirect()->back();
    }

}

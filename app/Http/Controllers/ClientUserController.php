<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client_user;
use Session;

class ClientUserController extends Controller
{
    public function index(){
        $users=Client_user::all();
        return view('admin.user.client_users',compact('users',$users));
    }

    public function edit($id){
      $user=Client_user::find($id);
      return view('admin/user/client_user_edit',compact('user',$user));
    }

    public function update(Request $request,$id){
      $user=Client_user::find($id);
      $user->member=$request->get('member');
      $user->save();
      Session::flash('success','Update Success');
      return redirect('/admin/client_user');
    }

    public function delete($id){
      Client_user::destroy($id);
      Session::flash('success','Delete Success');
      return redirect()->back();
    }

}

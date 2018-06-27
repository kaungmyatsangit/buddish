<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\User;
use Validator;

class UserController extends Controller
{
    public function index(){
        $data['users']=User::where('admin_level',0)->orWhere('admin_level',2)->orWhere('admin_level',3  )->get();
        return view('admin/user/index',$data);
    }

    public function edit($id){
        $data['user']=User::find($id);
        return view('/admin/user/edit',$data);
    }

    public function update(Request $request,$id){
        $user=User::find($id);
        $user->admin_level=$request->get('admin_level');
        $user->save();
        Session::flash('success','User Update Success');
        return redirect('/admin/user');
    }

    public function delete($id){
        User::destroy($id);
        Session::flash('success','User Update Success');
        return redirect('/admin/user');
    }

    public function register(){
      return view('/admin/user/register');
    }

    public function register_user(Request $request){
      $name=$request->get('name');
      $username=$request->get('username');
      $password=bcrypt($request->get('password'));
      $address=$request->get('address');
      $phone_number=$request->get('phone_number');


      User::create([
        'name'=>$name,
        'username'=>$username,
        'password'=>$password,
        'address'=>$address,
        'phone_number'=>$phone_number
      ]);


      return redirect('/admin/user');
    }

    public function resetpassword($id){
      $name=User::find($id);
      return view('admin/user/resetpassword',compact('user',$user));
    }

    public function reset(Request $request,$id){
      $validate=Validator::make($request->all(),[
        'old_password'=>'required',
        'new_password'=>'required|min:6',
        'password_confirmation'=>'required|confirmation|min:6'
      ]);

      if($validate->fails()){
        return redirect()->back()->withErrors($validate);
      }else{
        $old_password=$request->get('old_password');
        $password_true=$this->password_true($old_password,$id);
        if($password_true){

          $new_password=$request->get('new_password');
          $user=User::find($id);
          $user->password=bcrypt($new_password);
          $user->save();
          Session::flash('success','Password Update Success');
          return redirect('admin/user');
        }
      }

    }

    public function password_true($old_password,$id){
      $user=User::find($id);
      if(bcrypt($old_password)==$user->password){
        return true;
      }
    }

}

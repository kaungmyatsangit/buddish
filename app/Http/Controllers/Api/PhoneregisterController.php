<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client_user;

class PhoneregisterController extends Controller
{
    public function index(Request $request){
      $name=$request->get('name');
      $phone_number=$request->get('phone_number');
      $udid=$request->get('udid');
      $address=$request->get('address');

      $rersult=array();

      $user=$this->check($phone_number);
      if($user){

        $result['status']=1;
        $result['messgae']="Phone Number Already Exists";

        return response()->json(['data'=>$result]);

      }else{

        Client_user::create([
          'name'=>$name,
          'phone_number'=>$phone_number,
          'address'=>$address,
          'udid'=>$udid,
        ]);

        $result['status']=0;
        $result['message']="Register Success";

        return response()->json(['data'=>$result]);

      }

    }

    public function check($phone_number){
      $user=Client_user::where('phone_number',$phone_number)->exists();
      return $user;
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client_user;
use Session;

class ClientUserController extends ApiController
{
    public function __construct()
    {
        session_start();
    }

    public function store(Request $request)
    {
      $data=$request->all();
      $fbid=$data['fb_id'];
      $gplus=$data['gplusid'];
      $name=$data['name'];
      $phone_number=$data['phone_number'];
      $address=$data['address'];
      $udid=$data['udid'];
      // $fbid=$request->get('fb_id');
      // $phone_number=$request->get('phone_number');
      // $gplus=$request->get('gplus');
      // $name=$request->get('name');
      // $address=$request->get('address');
      // $udid=$request->get('udid');

      if(!empty($fbid) and empty($phone_number) and empty($gplus)){

          $checkFb=$this->checkFb($fbid);
          if($checkFb){
            $result=$this->getFbUser($fbid);
            return response()->json(['data'=>$result]);
          }else{
            $user=$this->insertUser($name,$fbid,$gplus,$phone_number,$address,$udid,1);
            return response()->json(['data'=>$user]);
          }

      }elseif (!empty($phone_number) and empty($fbid) and empty($gplus)) {

          $checkPhone=$this->checkPhone($phone_number);
          if($checkPhone){
            $user=$this->phoneUser($phone_number);
            return response()->json(['data'=>$user]);
          }else{
            $user=$this->insertUser($name,$fbid,$gplus,$phone_number,$address,$udid,2);
            return response()->json(['data'=>$user]);

          }

      }elseif (!empty($gplus) and empty($fbid) and empty($phone_number)) {

          $checkGplus=$this->checkGplus($gplus);
          if($checkGplus){
            $user=$this->gplusUser($gplus);
            return response()->json(['data'=>$user]);
          }else{
            $user=$this->insertUser($name,$fbid,$gplus,$phone_number,$address,$udid,3);
            return response()->json(['data'=>$user]);
          }
      }
    }

    public function checkFb($fbid)
    {
        $client_user = Client_user::where('fb_id', $fbid)->exists();
        if ($client_user) {
            return true;
        } else {
            return false;
        }
      }

      public function checkPhone($phone_number){
        $client_user = Client_user::where('phone_number', $phone_number)->exists();
        if ($client_user) {
            return true;
        } else {
            return false;
        }
      }

      public function checkGplus($gplus){
        $client_user = Client_user::where('gplus_id', $gplus)->exists();
        if ($client_user) {
            return true;
        } else {
            return false;
        }
      }

      public function getFbUser($fbid){
        $user=Client_user::where('fb_id',$fbid)->get()->toArray();

        return $this->responseUser($user);
      }

      public function phoneUser($phone_number){
        $user=Client_user::where('phone_number',$phone_number)->get()->toArray();
        return $this->responseUser($user);
      }

      public function gplusUser($gplus){
        $user=Client_user::where('gplus_id',$gplus)->get()->toArray();
        return $this->responseUser($user);
      }

      public function responseUser($user){
        $result=array();
        $result['id']=$user[0]['id'];
        $_SESSION['client_id']=$user[0]['id'];
        $result['name']=$user[0]['name'];
        $result['address']=$user[0]['address'];
        $result['fb_id']=$user[0]['fb_id'];
        $result['phone_number']=$user[0]['phone_number'];
        $result['gplus_id']=$user[0]['gplus_id'];
        $result['type']=$user[0]['type'];
        $result['member']=$user[0]['member'];
        return $result;
      }


      public function insertUser($name,$fbid,$gplus,$phone,$address,$udid,$type){
        $newUser = new Client_user();
        $newUser->name = $name;

        if(empty($fbid)){
          $newUser->fb_id='';
        }else{
          $newUser->fb_id =$fbid;
        }

        if(empty($gplus)){
          $newUser->gplus_id='';
        }else{
          $newUser->gplus_id=$gplus;
        }

        if(empty($phone)){
          $newUser->phone_number='';
        }else{
          $newUser->phone_number=$phone;
        }

        $newUser->address = $address;
        $newUser->udid = $udid;
        $newUser->type=$type;
        $newUser->save();

        $id = $newUser->id;
        $_SESSION['client_id']=$id;

        $result=array();
        $result['id']=$id;
        $result['name']=$name;
        $result['address']=$address;

        if(empty($fbid)){
          $result['fb_id']='';
        }else{
        $result['fb_id']=$fbid;
      }

        if(empty($gplus)){
          $result['gplus_id']='';

        }else{
          $result['gplus_id']=$gplus;

        }

        if(empty($phone)){
          $result['phone_number']='';

        }else{
          $result['phone_number']=$phone;

        }

        $result['udid']=$udid;
        $result['type']=$type;
        $result['member']=0;
        return $result;
      }

}

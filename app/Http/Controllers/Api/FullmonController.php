<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Fullmon;

class FullmonController extends Controller
{
  public function __construct()
  {
      date_default_timezone_set("Asia/Yangon");
  }


  public function index(){
    $today=date('Y-m-d');
    $tomorrow = date("Y-m-d", time() + 86400);
    $result=array();


    $today_noti=DB::select("select id,name,message from fullmons where date='$today'");
    $tomorrow_noti=DB::select("select id,name,message,date from fullmons where date='$tomorrow'");

    if($today_noti and $tomorrow_noti){
      $result['today_noti']['message']=$today_noti->message;
      $result['today_noti_status']=1;
      $result['tomorrow_noti']=$tomorrow_noti;
      $result['tomorrow_noti_stauts']=1;
    }elseif($today_noti and !$tomorrow_noti){
      $result['today_noti']['message']=$today_noti->message;
      $result['today_noti_status']=1;
      $result['tomorrow_noti']='';
      $result['tomorrow_noti_stauts']=0;
    }

    return response()->json(['data'=>$result]);


  }

}

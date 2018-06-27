<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pagoda;

class PagodaController extends Controller
{
    public function index($page){
      $page=$page;
      $perPage=10;
      $offest=($perPage*$page)-$perPage;
        $pagodas = Pagoda::where('active', 1)->offset($offest)->limit($perPage)->orderBy('id', 'desc')->get();
        $result=array();
        foreach ($pagodas as $pagoda) {
          $out['id']=$pagoda->id;
          $out['name']=$pagoda->name;
          $out['address']=$pagoda->address;
          $out['phone_number']=$pagoda->phone_number;
          $out['credit_name']=$pagoda->credit_name;
          $out['latitude']=$pagoda->latitude;
          $out['longtitude']=$pagoda->longtitude;
          $out['description']=$pagoda->description;
          $image=explode(',',$pagoda->image);

          for($i=0;$i<count($image);$i++){
            $out['image'][$i]=asset('/pagoda/') . '/' . $image[$i];
          }
          $result[]=$out;
          unset($out);
        }
        return response()->json(['data'=>$result,'status'=>1]);
    }
}

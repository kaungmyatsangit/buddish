<?php

namespace App\Http\Controllers\Api;

use App\Artist;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;


class ArtistController extends ApiController
{
    function index(){

        $artists=Artist::all();
        $result=array();
        foreach ($artists as $artist){
            $out['id']=$artist->id;
            $out['artist_type']=$artist->artist_type;
            $out['artist_name']=$artist->artist_name;
            $out['biography']=$artist->biography;
            $out['image']=asset('/artist/'.$artist->artist_image);
            $out['created_at']=date('m-d-Y',strtotime($artist->created_at));
            $result[]=$out;
            unset($out);
        }

        return $this->showAll(collect($result));
    }

    function show($id){
        $artist=Artist::find($id);
        if($artist){
            return $this->showOne($artist);
        }else{
            return $this->errorResponse('No artist',0,404);
        }
    }
    
    public function monk(){
        $artists=Artist::where('artist_type',"=",'monk')->get();
        $result=array();
        foreach ($artists as $artist){
            $out['id']=$artist->id;
            $out['artist_type']=$artist->artist_type;
            $out['artist_name']=$artist->artist_name;
            $out['biography']=$artist->biography;
            $out['image']=asset('/artist/'.$artist->artist_image);
            $out['created_at']=date('m-d-Y',strtotime($artist->created_at));
            $result[]=$out;
            unset($out);
        }

        return $this->showAll(collect($result));
    }

}

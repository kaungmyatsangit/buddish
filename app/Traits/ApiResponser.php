<?php

namespace App\Traits;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser{

    private function successResponse($data,$code){
        return response()->json($data,$code);
    }

    protected function errorResponse($message,$status,$code){
        return response()->json(['error'=>$message,'status'=>$status,'code'=>$code],$code);
    }

    protected function showAll(Collection $collection,$code=200){
        return $this->successResponse(['data'=>$collection,'status'=>1,'code'=>$code],$code);
    }

    protected function showOne(Model $model,$code=200){
        return $this->successResponse(['data'=>$model,'status'=>1,'code'=>$code],$code);
    }



}
